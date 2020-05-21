<?php


namespace App\Services;


use App\Exception\WrongRequestException;
use App\Model\Blog\Talk;
use App\Model\Blog\User;
use App\Model\Blog\UserLike;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Arr;
use Phper666\JwtAuth\Jwt;
use function Zipkin\Timestamp\now;

class UserService
{

    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;


    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    public const VERIFY_KEY = "user:verify:key:";


    public function login($email, $password, $ip, $isAdminLogin = true)
    {
        $userBuild = User::query()->where('email', $email);

        if ($isAdminLogin) {
            $userBuild->where('is_admin', 1);
        }

        $admin = $userBuild->first();
        if (empty($admin)) {
            throw new WrongRequestException("无此账户!");
        }

        if (!$this->verify($password, $admin->password)) {
            throw new WrongRequestException("账户密码错误!");
        }

        //拉黑
        if ($admin->status == 3) {
            throw new WrongRequestException("您已被拉入黑名单,无法登录!");
        }

        if ($admin->status == 1) {
            // 距今 1 天
            $lastLogin = strtotime($admin->last_login);

            $minus = time() - $lastLogin;

            $hours = (3600*24 - $minus);

            if ( $minus <= 60*60*24 ) {
                throw new WrongRequestException("您还在小黑屋中,距离解放还有".$hours.'秒');
            }
        }


        // 记录登录时间和IP
        $admin->last_login = date('Y-m-d H:i:s');
        $admin->last_ip    = ip2long($ip);
        $admin->save();

        $admin = $admin->toArray();

        $admin['uid'] = $admin['id'];

        $token = (string)$this->jwt->getToken($admin);

        return ['token' => $token, 'user' => $admin];
    }


    /**
     * @param $user
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model|object|null
     */
    public function getInfo($user)
    {
        return User::query()->where('id', $user['id'])->first();
    }

    /**
     * @param $params
     * 注册完自动登录
     *
     * @return array
     */
    public function register($params, $ip)
    {
        $name     = $params['name'];
        $email    = $params['email'];
        $password = $params['password'];


        User::query()->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $this->getHashPassword($password),
        ]);

        return $this->login($email, $password, $ip, false);
    }


    protected function getHashPassword($password)
    {
        return password_hash($password . config('halt'), PASSWORD_BCRYPT);
    }

    protected function verify($pass, $truePass)
    {
        return password_verify($pass . config('halt'), $truePass);
    }


    /**
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function logout()
    {
        $this->jwt->logout();
        return true;
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    public function list($params)
    {
        return User::query()
            ->filter($params)
            ->orderBy('id')->paginate(Arr::get($params, 'per_page', 10));
    }

    protected function getVerifyKey($userId){
        return self::VERIFY_KEY.$userId;
    }

    /**
     * @param $user
     */
    public function sendVerifyCode($user)
    {
        $code = substr(md5(json_encode($user)),0,4);
        $key = $this->getVerifyKey($user['id']);
        $this->redis->setex($key, 60, $code);
        MailService::sendVerifyCode($user['email'],$code);
        return true;
    }


    /**
     * @param $code
     * @param $user
     */
    public function verifyCode($code, $user)
    {
        $key = $this->getVerifyKey($user['id']);
        $codeCache = $this->redis->get($key);

        if ( strtolower($codeCache) ==  strtolower($code) ) {
            return User::query()->where('id', $user['id'])->update(['verify_at'=>date('Y-m-d H:i:s')]);
        }

        throw new WrongRequestException("令牌错误!");

    }

    /**
     * @param $params
     *
     * @return int
     */
    public function edit($params)
    {
        $user = User::query()->where('id', $params['id'])->first();

        if ($user->name != $params['name'] ) {
            if ( User::query()->where('name',$params['name'])->count() > 0 ) {
                throw new WrongRequestException("名称已存在!");
            }
        }

        if ( $user->email != $params['email']  ) {
            if ( User::query()->where('email', $params['email'])->count() > 0 ) {
                throw new WrongRequestException("邮箱已存在");
            }
        }


        $user->status = $params['status'];
        return $user->save();
    }

    /**
     * @param $user
     *
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function likeHistory($user)
    {
        return UserLike::query()->with(['user','article'])->where('user_id',$user['id'])->orderByDesc('id')->get();
    }

    /**
     * @param $params
     *
     * @return \Hyperf\Database\Model\Builder|\Hyperf\Database\Model\Model
     */
    public function talk($params)
    {
        return Talk::query()->create($params);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function deleteTalk($id)
    {
        return Talk::query()->where('id', $id)->update([
            'is_delete' => 1
        ]);
    }

    /**
     * @param $articleId
     *
     * @return \Hyperf\Database\Model\Builder[]|\Hyperf\Database\Model\Collection
     */
    public function talkList($articleId)
    {
        return Talk::query()
            ->with(['user','toUser'])
            ->where('article_id',$articleId)
            ->where('is_delete',0)
            ->orderBy('id')
            ->get();
    }

    /**
     * @param $email string 发送密码找回code
     */
    public function sendFindPassCode($email)
    {
        $user =User::query()->where('email', $email)->first();

        if ( empty($user) ) {
            throw new WrongRequestException("无此账号!");
        }

        if ($user->verify_at == null ) {
            throw new WrongRequestException("该邮箱未激活!");
        }

        $key = $this->getVerifyKey($email);
        $code = substr(md5(time().rand(1,10000).$email),0,4);

        $this->redis->setex($key,60,$code);

        MailService::sendVerifyCode($email, $code);

        return true;
    }

    /**
     * @param $email
     * @param $pass
     * @param $code
     *
     * @return int
     */
    public function updatePassWord($email,$pass, $code)
    {
        $key = $this->getVerifyKey($email);

        $codeCache = $this->redis->get($key);

        if ( strtolower($codeCache) != strtolower($code)) {
            throw new WrongRequestException("令牌验证失败!");
        }

        return User::query()->where('email', $email)->update([
            'password' => $this->getHashPassword($pass)
        ]);
    }
}

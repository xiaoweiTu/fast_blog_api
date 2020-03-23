<?php


namespace App\Services;


use App\Exception\WrongRequestException;
use App\Model\Blog\User;
use Hyperf\Di\Annotation\Inject;
use Phper666\JwtAuth\Jwt;

class UserService {

    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;


    public function login($email, $password)
    {
        $admin = User::query()->where('email',$email)->where('is_admin',1)->first();
        if (empty($admin)) {
            throw new WrongRequestException("无此账户!");
        }

        if (!$this->verify($password, $admin->password)) {
            throw new WrongRequestException("账户密码错误!");
        }

        $admin = $admin->toArray();

        $admin['uid'] = $admin['id'];

        $token = (string)$this->jwt->getToken($admin);

        return ['token'=>$token,'user'=>$admin];
    }


    protected function verify($pass, $truePass) {
        return password_verify($pass.config('halt'),$truePass);
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
}
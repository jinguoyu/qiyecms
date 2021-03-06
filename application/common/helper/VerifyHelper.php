    <?php
    /**
     * Created by PhpStorm.
     * User: 申法宽
     * Date: 16/4/25
     * Time: 13:19
     * Email: sfk@live.cn
     * File: VerifyHelper.php
     */
    namespace app\common\helper;
    use Gregwar\Captcha\CaptchaBuilder;
    class VerifyHelper
    {
        /**
         * 生成验证码
         */
        public static function verify()
        {
            $builder = new CaptchaBuilder();
            $builder->build()->output();
            session('verify_code', $builder->getPhrase());
        }
        /**
         * 检测验证码是否正确
         * @param $code
         * @return bool
         */
        public static function check($code)
        {
            return ($code == session('verify_code') && $code != '') ? true : false;
        }
    }
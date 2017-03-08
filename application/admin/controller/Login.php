<?php

namespace app\admin\controller;

use controller\BasicAdmin;
use think\Db;

/**
 * 系统登录控制器
 * class Login
 * @package app\admin\controller
 */
class Login extends BasicAdmin {

    /**
     * 控制器基础方法
     */
    public function _initialize() {
        if ($this->_isLogin() && $this->request->action() !== 'out') {
            $this->redirect('@admin');
        }
    }

    /**
     * 用户登录
     * @return string
     */
    public function index() {
        if ($this->request->isGet()) {
            return $this->fetch();
        } else {
            $username = $this->request->post('username', '', 'trim');
            $password = $this->request->post('password', '', 'trim');
            (empty($username) || strlen($username) < 4) && $this->error('登录账号长度不能少于4位有效字符!');
            (empty($password) || strlen($password) < 4) && $this->error('登录密码长度不能少于4位有效字符!');
            $user = Db::name('SystemUser')->where('username', $username)->find();
            empty($user) && $this->error('登录账号不存在，请重新输入!');
            ($user['password'] !== md5($password)) && $this->error('登录密码与账号不匹配，请重新输入!');
            session('user', $user);
            $this->success('登录成功，正在进入系统...', '@admin');
        }
    }
    /**
     * 退出登录
     */
    public function out() {
        session('user', null);
        $this->success('退出登录成功！', '@admin/login');
    }

}

<?php

namespace Goteo\Application;

use Goteo\Model\User;

/**
 * Class for dealing with $_SESSION related stuff
 */
class Session {
    static protected $session_expire_time = 3600;
    static protected $start_time = 0;
    static protected $triggers = array('session_expires' => null, 'session_destroyed' => null);

    static public function setSessionExpires($time) {
        self::$session_expire_time = (int) $time;
    }
    static public function setStartTime($start_time) {
        self::$start_time = (int) $start_time;
    }
    static public function getSessionExpires() {
        return self::$session_expire_time;
    }
    static public function getStartTime() {
        return self::$start_time ? self::$start_time : microtime(true);
    }
    static public function expiresIn() {
        return self::getStartTime() + self::getSessionExpires() - (int)self::get('session_init');
    }
    static public function renew() {
        self::store('init_time', self::getStartTime());
    }
    /**
     * Starts session
     * @param  string $name [description]
     * @return [type]       [description]
     */
    static public function start($name = 'Goteo', $session_time = null) {
        self::setStartTime(microtime(true));
        session_name($name);
        session_start();
        if(!self::exists('init_time')) {
            self::store('init_time', self::getStartTime());
        }
        if($session_time) {
            self::setSessionExpires($session_time);
        }
        if( self::getStartTime() > self::get('init_time') + self::getSessionExpires() ) {
            // expires session
            self::destroy(false);
            $callback = self::$triggers['session_expires'];
            if(is_callable($callback)) {
                $callback();
            }
        }
    }

    /**
     * Expires session
     * @return [type] [description]
     */
    static public function destroy($throw_callback = true) {
        session_unset();
        session_destroy();
        session_write_close();
        session_regenerate_id(true);
        session_start();
        $callback = self::$triggers['session_destroyed'];
        if($throw_callback && is_callable($callback)) {
            $callback();
        }
    }

    /**
     * Stores some value in session
     * @param  [type] $key   [description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    static public function store($key, $value) {
        return $_SESSION[$key] = $value;
    }

    static public function get($key) {
        return $_SESSION[$key];
    }

    static public function exists($key) {
        return is_array($_SESSION) && array_key_exists($key, $_SESSION);
    }

    static public function onSessionExpires($callback) {
        if(is_callable($callback)) {
            self::$triggers['session_expires'] = $callback;
        }
    }
    static public function onSessionDestroyed($callback) {
        if(is_callable($callback)) {
            self::$triggers['session_destroyed'] = $callback;
        }
    }

    static public function setUser(User $user) {
        if(self::store('user', $user)) return $user;
        return false;
    }
    /**
     * Comprueba si el usuario está identificado.
     *
     * @return boolean
     */
    static public function isLogged () {
        return (self::get('user') instanceof User);
    }

    /**
     * Returns user id if logged
     *
     * @return boolean
     */
    static public function getUserId () {
        return (self::isLogged()) ? self::get('user')->id : false;
    }

    /**
     * Returns user object if logged
     *
     * @return boolean
     */
    static public function getUser () {
        return (self::isLogged()) ? self::get('user') : false;
    }
}

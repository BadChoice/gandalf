<?php namespace BadChoice\Gandalf;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;

class Gandalf{

    const PHONE   = 0;
    const TABLET  = 1;
    const DESKTOP = 2;
    const UNKNOWN = 3;

    /**
     * Tracks the user and returns its DeviceLogin if new
     * @return DeviceLogin
     */
    public static function trackUser($allowNew = true){
        $hash = Cookie::get(static::cookieName());
        if($hash == null){
            return static::save($allowNew);
        }
        else{
            return static::update($allowNew);
        }
    }

    //==================================================
    // DATABASE
    //==================================================
    private static function save($allowNew = true){
        $hash = hash('sha256',Agent::device() . Agent::browser() . Agent::platform() . static::getDeviceType());
        Cookie::queue( static::cookieName(), $hash, time()+60*60*24*1000); //Expires in 1000 days

        $location = Location::get();

        return DeviceLogin::create([
            "user_id"       => Auth::user()->id,
            "device"        => Agent::device(),
            "active"        => $allowNew,
            "browser"       => Agent::browser(),
            "device_type"   => static::getDeviceType(),
            "platform"      => Agent::platform(),
            "location"      => $location->countryName . ', ' . $location->cityName,
            "ip"            => $location->ip,
            "hash"          => $hash
        ]);
    }

    /**
     * Returns the DeviceLogin if new
     * @return DeviceLogin
     */
    private static function update($allowNew){
        $hash       = hash('sha256',Agent::device() . Agent::browser() . Agent::platform() . static::getDeviceType());
        $location   = Location::get();
        $deviceLogin = DeviceLogin::whereHash($hash)->first();
        if($deviceLogin){
            $deviceLogin->update([
                "location"      => $location->countryName . ', ' . $location->cityName,
                "ip"            => $location->ip,
            ]);
            return null;
        }
        else{
            return static::save($allowNew);
        }
    }

    //==================================================
    // HELPERS
    //==================================================
    private static function cookieName(){
        return 'revo_login_' . Auth::user()->id;
    }

    private static function getDeviceType(){
        if(Agent::isIphone())   return Gandalf::PHONE;
        if(Agent::isTablet())   return Gandalf::TABLET;
        if(Agent::isDesktop())  return Gandalf::DESKTOP;
        return Gandalf::UNKNOWN;
    }

    //==================================================
    // LOG
    //==================================================
    public static function log(){
        echo Agent::device()    . "<br>";
        echo Agent::browser()   . "<br>";
        echo Agent::platform()  . "<br>";
        echo "isPhone: "   . Agent::isPhone()   . "<br>";
        echo "isMobile: "  . Agent::isMobile()  . "<br>";
        echo "isTablet:"   . Agent::isTablet()  . "<br>";
        echo "isDesktop: " . Agent::isDesktop() . "<br>";
        echo "isLaptop: " .  Agent::isLaptop() . "<br>";
        echo Request::getClientIp()             . "<br>";

        //$location = Location::get('213.148.199.126');
        $location = Location::get();
        echo $location->countryName . "<br>";
        echo $location->cityName . "<br>";
        echo $location->ip . "<br>";

        //dd($location);
    }
}
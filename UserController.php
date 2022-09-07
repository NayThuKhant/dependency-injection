<?php

class UserController
{
    public function index(User $user){
        print($user->all());
    }
}

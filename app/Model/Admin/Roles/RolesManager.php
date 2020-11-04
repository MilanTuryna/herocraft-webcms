<?php


namespace App\Model\Admin\Roles;


use App\Model\UserRepository;

/**
 * Class RolesManager
 * @package App\Model\Admin
 */
class RolesManager
{
    private UserRepository $userRepository;


    /**
     * RolesManager constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository(): UserRepository
    {
        return $this->userRepository;
    }
}
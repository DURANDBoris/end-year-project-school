<?php

namespace App\Utils;

use App\Entity\Folder;
use App\Entity\UserInOrganization;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Organization;
use App\Entity\User;


// This class provide protection by verifying if an action is allowed or not.
// e.g : An user can only update it's own info

class CustomFireWall {

    public function preventUser(User|null $currentUser, User $targetUser) {
        if ($currentUser === null) return false;
        if ( $currentUser->getId() !== $targetUser->getId() ) { return false; }
        return true;
    }
    public function preventOrganization(User $currentUser, Organization $targetOrganization) {
        if ( $currentUser->getId() !== $targetOrganization->getOwner()->getId() ) { return false; }
        return true;
    }


    public function preventUserInOrganization(User $currentUser, array $userInOrganizations) {
        foreach ($userInOrganizations as $userInOrganization) {
            if( $currentUser->getId() === $userInOrganization->getIdUser()->getId()) return true;
        }
        return false;
    }

    public function isAlreadyInOrganization(User $targetUser, array $userInOrganizations){
        foreach ($userInOrganizations as $userInOrganization) {
            if( $targetUser->getId() === $userInOrganization->getIdUser()->getId()) return false;
        }
        return true;
    }


}
?>
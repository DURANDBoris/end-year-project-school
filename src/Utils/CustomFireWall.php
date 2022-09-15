<?php

namespace App\Utils;

use App\Entity\Organization;
use App\Entity\User;


// This class provide protection by verifying if an action is allowed or not.
// e.g : An user can only update it's own info

class CustomFireWall {

    public function preventUser(User $currentUser, User $targetUser) {
        if ( $currentUser->getId() !== $targetUser->getId() ) { return false; }
        return true;
    }
    public function preventOrganization(User $currentUser, Organization $organization) {
        if ( $currentUser->getId() !== $organization->getOwner()->getId() ) { return false; }
        return true;
    }


}
?>
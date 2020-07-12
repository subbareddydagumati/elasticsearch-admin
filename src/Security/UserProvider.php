<?php

namespace App\Security;

use App\Exception\CallException;
use App\Manager\CallManager;
use App\Model\CallRequestModel;
use App\Security\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Response;

class UserProvider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(CallManager $callManager)
    {
        $this->callManager = $callManager;
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * If you're not using these features, you do not need to implement
     * this method.
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername(string $email): ?User
    {
        // Load a User object from your data source or throw UsernameNotFoundException.
        // The $username argument may not actually be a username:
        // it is whatever value is being returned by the getUsername()
        // method in your User class.

        return $this->getUserByEmail($email);
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        // Return a User object after making sure its data is "fresh".
        // Or throw a UsernameNotFoundException if the user no longer exists.
        return $this->getUserByEmail($user->getEmail());
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass($class)
    {
        return User::class === $class;
    }

    /**
     * Upgrades the encoded password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        // TODO: when encoded passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newEncodedPassword);
    }

    private function getUserByEmail($email)
    {
        $callRequest = new CallRequestModel();
        $callRequest->setMethod('HEAD');
        $callRequest->setPath('/.elastictsearch-admin-users');
        $callResponse = $this->callManager->call($callRequest);

        if (Response::HTTP_OK == $callResponse->getCode()) {
            $query = [
                'q' => 'email:"'.$email.'"',
            ];
            $callRequest = new CallRequestModel();
            $callRequest->setPath('/.elastictsearch-admin-users/_search');
            $callRequest->setQuery($query);
            $callResponse = $this->callManager->call($callRequest);
            $results = $callResponse->getContent();

            if (1 == count($results['hits']['hits'])) {
                foreach ($results['hits']['hits'] as $row) {
                    $row = $row['_source'];

                    $user = new User();
                    $user->setEmail($row['email']);
                    $user->setPassword($row['password']);
                    $user->setRoles($row['roles']);
                    if (true == isset($row['created_at']) && '' != $row['created_at']) {
                        $user->setCreatedAt(new \Datetime($row['created_at']));
                    }
                    return $user;
                }
            }
        }

        return null;
    }
}
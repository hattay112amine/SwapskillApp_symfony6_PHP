<?php

namespace App\DataFixtures;

use App\Entity\ExchangeProposal;
use App\Entity\User;
use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ExchangeProposalFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $this->getReference('user_test', User::class);
        $user2 = $this->getReference('admin_test', User::class);

        $skill1 = $this->getReference('skill_user1_0', Skill::class); // PHP
        $skill2 = $this->getReference('skill_user2_0', Skill::class); // React

        $proposal1 = new ExchangeProposal();
        $proposal1->setRequester($user1);
        $proposal1->setReceiver($user2);
        $proposal1->setOfferedSkill($skill1);   // user1 offers PHP
        $proposal1->setRequestedSkill($skill2); // wants React
        $proposal1->setProposal('Can we swap next week?');
        $proposal1->setStatus('pending');
        $proposal1->setCreatedAt(new \DateTime());
        $manager->persist($proposal1);

        $proposal2 = new ExchangeProposal();
        $proposal2->setRequester($user2);
        $proposal2->setReceiver($user1);
        $proposal2->setOfferedSkill($skill2);   // user2 offers React
        $proposal2->setRequestedSkill($skill1); // wants PHP
        $proposal2->setProposal('Available this weekend');
        $proposal2->setStatus('pending');
        $proposal2->setCreatedAt(new \DateTime());
        $manager->persist($proposal2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SkillFixtures::class,
        ];
    }
}

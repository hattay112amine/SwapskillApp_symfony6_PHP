<?php

namespace App\DataFixtures;

use App\Entity\Skill;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SkillFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user1 = $this->getReference('user_test', User::class);
        $user2 = $this->getReference('admin_test', User::class);

        $skillsUser1 = ['PHP', 'JavaScript', 'Python', 'Java', 'C#'];
        $skillsUser2 = ['React', 'Angular', 'Vue.js', 'Node.js', 'Laravel'];

        foreach ($skillsUser1 as $index => $title) {
            $skill = new Skill();
            $skill->setTitle($title);
            $skill->setOwner($user1);
            $manager->persist($skill);
            $this->addReference('skill_user1_' . $index, $skill);
        }

        foreach ($skillsUser2 as $index => $title) {
            $skill = new Skill();
            $skill->setTitle($title);
            $skill->setOwner($user2);
            $manager->persist($skill);
            $this->addReference('skill_user2_' . $index, $skill);
        }

        $manager->flush();
    }

//
    // This ensures UserFixtures loads first
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}

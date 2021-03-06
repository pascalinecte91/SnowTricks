<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class CategoriesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
       
        $listCategory = [
    1 => ['title' => 'grabs'],
    2 => ['title' => 'straight airs'],
    3 => ['title' => 'rotation'],
    4 => ['title' => 'spins'],
    5 => ['title' => 'flips and inverted rotations'],
    6 => ['title' => 'inverted hand plants'],
    7 => ['title' => 'stalls'],
    8 => ['title' => 'slides'],
        
 ];
  
        foreach($listCategory as $key => $value){
            $listCategory = new Category();
            $listCategory->setTitle($value['title']);
            $listCategory->setCreatedAt($faker->dateTimeBetween('- 1 years'));
            $listCategory->setUpdateAt($faker->dateTimeInInterval('+5 days'));
            $listCategory->setDescription(($faker->paragraph(2)));
            $manager->persist($listCategory);

            //enregistrer la categorie  dans une reference
            $this->addReference('category_' . $key, $listCategory);
        }
            
            $manager->flush();
    }
    public function getOrder()
    {
        return 2;
    }
}




<?php

namespace Dende\FrontBundle\DataFixtures\ORM;

use Dende\FrontBundle\DataFixtures\BaseFixture;
use Dende\FrontBundle\Entity\Project;

class ProjectsData extends BaseFixture
{
    public function getOrder()
    {
        return 1;
    }

    public function insert($params)
    {
        $project = new Project();

        $project->setName($params["name"]);

        if (isset($params["features"])) {
            $project->setFeatures($params["features"]);
        }

        if (isset($params["tags"])) {
            $project->setTags($params["tags"]);
        }

        if (isset($params["pictures"])) {
            $project->setPictures($params["pictures"]);
        }

        if (isset($params["company"])) {
            $project->setCompany($params["company"]);
        }

        if (isset($params["description"])) {
            $project->setDescription($params["description"]);
        }

        return $project;
    }
}

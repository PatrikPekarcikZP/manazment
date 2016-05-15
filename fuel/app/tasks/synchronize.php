<?php
namespace Fuel\Tasks;

use Auth\Auth;
use Fuel\Core\Config;
use Fuel\Core\Str;
use Gitlab\Api\Groups;
use Gitlab\Api\Projects;
use Gitlab\Api\Repositories;
use Gitlab\Api\Users;
use Gitlab\Exception\ErrorException;
use Gitlab\Exception\RuntimeException;
use Gitlab\Model\Branch;
use Gitlab\Model\Group;

class Gitlab_Group
{
    private $name;
    private $text;

    /** @var Gitlab_Project[] */
    private $projects = [];

    public function __construct($name, $text)
    {
        $this->name = $name;
        $this->text = $text;
    }

    /**
     * @param Gitlab_Project $project
     * @return Gitlab_Project
     */
    public function addProject(Gitlab_Project $project)
    {
        $this->projects[] = $project;
        return $project;
    }

    public static function groupName(\Model_Ustav $ustav, \Model_Predmet $predmet)
    {
        return $ustav->skratka . "_" . $predmet->skratka;
    }

    /**
     * @param \Gitlab\Client $apiClient
     * @return boolean
     */
    public function apiCheckAndCreate($apiClient)
    {
        /** @var \Gitlab\Api\Groups $groupsApi */
        /** @var Group $group */
        $groupsApi = $apiClient->api('groups');
        try {
            $group = $groupsApi->show($this->name);
            if ($group['description'] == $this->text) {
                // TODO: upravit skupinu (tuto funkcionalitu treba pridat do gitlab-api kniznice)
            }
        } catch (RuntimeException $e) {
            if ($e->getMessage() == "404 Not found") {
                $user_id = Config::get("gitlab.api_userid");
                $groupsApi->create($this->name, $this->name, $this->text);
                $groupsApi->addMember($this->name, $user_id, 50);
                $groupsApi->removeMember($this->name, 1);
            }
        }
        return true;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * @var int
     */
    private $namespace_id = null;

    /**
     * @param \Gitlab\Client $apiClient
     * @return int
     */
    public function getNamespaceId($apiClient)
    {
        if ($this->namespace_id == null) {
            /** @var Groups $groupsApi */
            $groupsApi = $apiClient->api('groups');
            $group = $groupsApi->show($this->name);
            $this->namespace_id = $group['id'];
        }
        return $this->namespace_id;
    }
}

class Gitlab_Project
{
    private $name;
    private $text;
    private $branchRok;

    private $privileges = [];

    public function __construct($name, $text, $rok)
    {
        $this->name = $name;
        $this->text = $text;
        $this->branchRok = $rok;
    }

    /**
     * @param Gitlab_User $user
     * @param boolean $admin
     * @return Gitlab_User
     */
    public function addPrivilege(Gitlab_User $user, $admin)
    {
        $this->privileges[] = [$user, $admin];
        return $user;
    }

    /**
     * @param Gitlab_Group $group
     * @param \Gitlab\Client $apiClient
     * @return bool
     */
    public function apiCheckAndCreate($group, $apiClient)
    {
        /** @var Projects $projectsApi */
        $projectsApi = $apiClient->api('projects');
        try {
            $projekt = $projectsApi->show($group->getName() . "/" . $this->name);
        } catch (RuntimeException $e) {
            if ($e->getMessage() == "404 Project Not Found") {
                $projekt = $projectsApi->create($this->name, [
                    'namespace_id' => $group->getNamespaceId($apiClient), // (optional) - namespace for the new project (defaults to user)
                    'description' => $this->text, // (optional) - short project description
                    'issues_enabled' => 1, // (optional)
                    'merge_requests_enabled' => 1, // (optional)
                    'builds_enabled' => 1, // (optional)
                    'wiki_enabled' => 1, // (optional)
                    'snippets_enabled' => 0, // (optional)
                    'public' => 0, // (optional) - if true same as setting visibility_level = 20
                    'visibility_level' => 0, // (optional)
                    'public_builds' => 0 // (optional)
                ]);
            } else {
                throw $e;
            }
        }
        $projekt_id = $projekt['id'];

        // nastavenie pristupovych prav
        foreach ($this->privileges as $privilege) {
            /** @var Gitlab_User $user */
            list($user, $admin) = $privilege;
            $user->apiInitUser($apiClient);
            $user_id = $user->getUserId();
            $projectsApi->addMember($projekt_id, $user_id, $admin ? 50 : 10);
        }

        // nastavenie branchov
        /** @var Repositories $repositoriesApi */
        $repositoriesApi = $apiClient->api('repositories');
        $branches = $repositoriesApi->branches($projekt_id);

        if (count($branches) == 0) {
            $repositoriesApi->createFile($projekt_id, "README.md", "# Projekt pre rok " . $this->branchRok, "master-" . $this->branchRok, "year creation");
        } else {
            $created = false;
            foreach ($branches as $branch) {
                if ($branch['name'] == "master-" . $this->branchRok) {
                    $created = true;
                }
            }
            if (!$created) {
                $repositoriesApi->createBranch($projekt_id, "master-" . $this->branchRok, $branches[0]['commit']['id']);
            }
        }

        return true;
    }
}

class Gitlab_User
{
    private $name;
    private $mail;
    private $user_id = null;

    public function __construct($name, $mail)
    {
        $this->name = $name;
        $this->mail = $mail;
    }

    /**
     * @param \Gitlab\Client $apiClient
     */
    public function apiInitUser($apiClient)
    {
        if ($this->user_id == null) {
            /** @var Users $usersApi */
            $usersApi = $apiClient->api("users");
            $users = $usersApi->search($this->mail);

            foreach ($users as $user) {
                if ($user['email'] == $this->mail) {
                    $this->user_id = $user['id'];
                    break;
                }
            }

            if ($this->user_id == null) {
                $heslo = Str::random();
                // TODO: ulozit a vytlacit hesla / alebo navigovat na resetovanie hesla
                $user = $usersApi->create($this->mail, $heslo, [
                    'name' => $this->name,
                    'username' => str_replace("@", "-", $this->mail)
                ]);
                $this->user_id = $user['id'];
            }
        }
    }

    public function getUserId()
    {
        if ($this->user_id == null) {
            throw new \Exception("Užívateľ nebol inicializovaný!");
        }
        return $this->user_id;
    }
}

class Synchronize
{
    const VYUCBA_PROJECT_NAME = 'vyucba';
    const VYUCBA_PROJECT_TEXT = 'Výučba';

    const STUDENT_PROJECT_TEXT = 'Študentský projekt {:name}';

    private $errtext = null;

    public function run()
    {
        Config::load('gitlab', 'gitlab');
        echo "Príprava štruktúry ...";
        $groups = $this->getStructure(\Model_Vyucba::find_otvorena());
        if ($groups == null) {
            echo " [ERROR]\n";
            return;
        }
        echo " [OK]\n";
        echo "Počet skupín: " . count($groups) . "\n";

        echo "Synchronizácia s GitLab ...";
        if ($this->sync($groups)) {
            echo " [OK]\n";
        } else {
            echo " [ERROR] " . $this->errtext . "\n";
        }
    }

    public function user($name = null)
    {
        try {
            $apiClient = $this->api($name != null);

            /** @var Users $usersApi */
            $usersApi = $apiClient->api('users');
            $user = [];
            if ($name == null) {
                $user = $usersApi->me();
                echo "Prihlaseny pouzivatel:\n";
            } else {
                $users = $usersApi->search($name);
                if (count($users) > 0) {
                    $user = $users[0];
                }
            }
            foreach ($user as $k => $v) {
                if (is_string($v) && !empty($v)) {
                    echo "[$k] $v\n";
                }
            }
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function sudo()
    {
        $apiClient = $this->api(true);

        /** @var Users $usersApi */
        $usersApi = $apiClient->api('users');
        $me = $usersApi->me();
        echo "Prihlaseny pouzivatel (sudo):\n";
        foreach ($me as $k => $v) {
            if (is_string($v) && !empty($v)) {
                echo "[$k] $v\n";
            }
        }
    }

    public function help()
    {
        echo <<<HELP
            Usage:
                php oil refine synchronize

            Description:
                Task na synchronizáciu GitLab Uni aplikácie s repozitárom zdrojových kódov

            Examples:
                php oil r synchronize
                php oil r synchronize:user [name]
                php oil r synchronize:sudo
                php oil r synchronize:help

HELP;
    }

    /**
     * @param \Model_Vyucba[] $vyucby
     * @return array
     * @throws \Exception
     */
    private function getStructure($vyucby)
    {
        $groups = [];
        foreach ($vyucby as $vyucba) {
            $groupKey = Gitlab_Group::groupName($vyucba->predmet->ustav, $vyucba->predmet);
            if (isset($groups[$groupKey])) {
                throw new \Exception("Nieco je zle, skupina nemoze byt 2 krat!");
            }

            $skupina = new Gitlab_Group($groupKey, $vyucba->predmet->nazov . " (" . $vyucba->predmet->ustav->nazov . ")");
            $vyucbaProjekt = $skupina->addProject(new Gitlab_Project(self::VYUCBA_PROJECT_NAME, self::VYUCBA_PROJECT_TEXT, $vyucba->rok));
            $studenti = $vyucba->getStudenti();
            /** @var Gitlab_Project[] $studentskeProjekty */
            $studentskeProjekty = [];
            foreach ($studenti as $student) {
                $text = $student->getText();
                $name = $student->getName();
                $studentskyProjekt = $skupina->addProject(new Gitlab_Project($name, str_replace("{:name}", $text, self::STUDENT_PROJECT_TEXT), $vyucba->rok));
                $studentUser = $studentskyProjekt->addPrivilege(new Gitlab_User($text, $student->mail), true);
                $vyucbaProjekt->addPrivilege($studentUser, false);
                $studentskeProjekty[] = $studentskyProjekt;
            }

            foreach ($vyucba->getVyucujuci() as $vyucujuci) {
                $vyucujuciUser = $vyucbaProjekt->addPrivilege(new Gitlab_User($vyucujuci->getText(), $vyucujuci->mail), true);
                foreach ($studentskeProjekty as $projekt) {
                    $projekt->addPrivilege($vyucujuciUser, false);
                }
            }

            $groups[$groupKey] = $skupina;

        }
        return $groups;
    }

    /**
     * @param Gitlab_Group[] $groups
     * @return bool
     */
    private function sync($groups)
    {
        $apiClient = $this->api(true);

        try {
            foreach ($groups as $group) {
                if (!$group->apiCheckAndCreate($apiClient)) {
                    $this->errtext = "Chyba vytvarania skupiny " . $group->getName();
                    return false;
                }
                foreach ($group->getProjects() as $project) {
                    if (!$project->apiCheckAndCreate($group, $apiClient)) {
                        $this->errtext = "Chyba vytvarania projektu " . $group->getName();
                        return false;
                    }
                }

            }
        } catch (RuntimeException $e) {
            $this->errtext = $e->getMessage();
            return false;
        }

        return true;
    }

    private function api($sudo = false)
    {
        Config::load('gitlab', 'gitlab');
        $config = Config::get('gitlab');
        $apiClient = new \Gitlab\Client($config['uri'] . '/api/v3/');
        $apiClient->authenticate($config['api_token'], \Gitlab\Client::AUTH_URL_TOKEN, $sudo ? $config['api_sudo'] : null);
        return $apiClient;
    }
}
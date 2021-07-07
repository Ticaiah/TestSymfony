<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

//Yaml controller class, used to manage data in the yaml file
class YamlController extends AbstractController
{
    private $value;
    /**
     * @Route("/yaml", name="yaml")
     */
    //Constructor of the class, used to open the yaml file
    public function __construct()
    {
        $this->value = Yaml::parseFile('C:\wamp64\www\TestSymfonyService\src/organizations.yaml'); //TODO: Mettre un path relatif;
    }
    //index function ; main index, used to show all organizations
    public function index(): Response
    {
        return $this->render('yaml/index.html.twig',[
            'yamlFile'=>$this->value['organizations']]);
    }

    /**
     * @Route("/yaml/{organization}", name="organization")
     */
    //indexOrganization function : Organization index, used to show index properties and list users from this organization
    public function indexOrganization($organization): Response
    {
        $index=null;
        //We look for the index of the organization in the yaml
        foreach ($this->value['organizations'] as $i=>$org)
        {
            if ($org['name']==$organization)
            {
                $index=$i;
            }
        }
            return $this->render('yaml/organization.html.twig',[
                'organization'=>$this->value['organizations'][$index],
                'viewMode'=>'view']);
    }

    /**
     * @Route("/yaml/add", name="addOrganization")
     */
    //addOrganization function : on get method, return a form to add a new organization.
    //On post method, will add the data in the yaml file
    public function addOrganization(Request $request): Response
    {
        if ($request->isMethod('get')) {
            return $this->render('yaml/organization.html.twig',[
                'viewMode'=>'add']);
        }
        //On post method, we get data and add it to the file
        else if ($request->isMethod('post')) {
            $params = $request->request->all();
            array_push($this->value['organizations'], array('name'=>$params['organization'], 'description'=>$params['description']));
            $yaml = Yaml::dump($this->value);
            file_put_contents('C:\wamp64\www\TestSymfonyService\src/organizations.yaml', $yaml);
            return $this->redirectToRoute('organization',['organization' => $params['organization']]);
            //On ajoute une nouvelle orga
        }
    }

    /**
     * @Route("/yaml/edit/{organization}", name="editOrganization")
     */
    //editOrganisation function : used to edit data from an organisation.
    //On get method, will return a pre-filled form from existing data.
    //on post method, will add the new changes to the yaml file
    public function editOrganization(Request $request, $organization): Response
    {
        $index=null;
        foreach ($this->value['organizations'] as $i=>$org)
        {
            if ($org['name']==$organization)
            {
                $index=$i;
            }
        }
        if ($request->isMethod('get')) {
            return $this->render('yaml/organization.html.twig',[
                'organization'=>$this->value['organizations'][$index],
                'viewMode'=>'edit']);
        }

        else if ($request->isMethod('post')) {
            $params = $request->request->all();
            $this->value['organizations'][$index]['name'] = $params['organization'];
            $this->value['organizations'][$index]['description'] = $params['description'];
            $yaml = Yaml::dump($this->value);
            file_put_contents('C:\wamp64\www\TestSymfonyService\src/organizations.yaml', $yaml);
            return $this->redirectToRoute('organization',['organization' => $params['organization']]);
        }
    }

    /**
     * @Route("/yaml/remove/{organization}", name="removeOrganization")
     */
    //deleteOrganization function : used to delete an organization
    public function deleteOrganization(Request $request, $organization)
    {
        $index=null;
        foreach ($this->value['organizations'] as $i=>$org)
        {
            if ($org['name']==$organization)
            {
                $index=$i;
            }
        }
        unset($this->value['organizations'][$index]);
        $yaml = Yaml::dump($this->value);
        file_put_contents('C:\wamp64\www\TestSymfonyService\src/organizations.yaml', $yaml);
        return $this->redirectToRoute('yaml');
    }

    /**
     * @Route("/yaml/users/edit/{organization}/{user}", name="user")
     */
    //indexUser function : same as indexOrganization
    public function indexUser(Request $request, $organization, $user): Response
    {
        $index=null;
        foreach ($this->value['organizations'] as $i=>$org)
        {
            if ($org['name']==$organization)
            {
                $index=$i;
            }
        }
        foreach ($this->value['organizations'][$index]['users'] as $i=>$userName)
        {
            if ($userName['name']==$user)
            {
                $indexName=$i;
            }
        }
        return $this->render('yaml/user.html.twig',[
            'organization'=>$this->value['organizations'][$index],
            'user'=>$this->value['organizations'][$index]['users'][$indexName],
            'viewMode'=>'view']);
    }

    /**
     * @Route("/yaml/users/{organization}/{user}", name="editUser")
     */
    //editUser function : same as editOrganization
    public function editUser(Request $request, $organization, $user)
    {
        $index=null;
        foreach ($this->value['organizations'] as $i=>$org)
        {
            if ($org['name']==$organization)
            {
                $index=$i;
            }
        }
        foreach ($this->value['organizations'][$index]['users'] as $i=>$userName)
        {
            if ($userName['name']==$user)
            {
                $indexName=$i;
            }
        }
        if ($request->isMethod('get')) {
        return $this->render('yaml/user.html.twig',[
            'organization'=>$this->value['organizations'][$index],
            'user'=>$this->value['organizations'][$index]['users'][$indexName],
            'viewMode'=>'edit']);
        }
        else if ($request->isMethod('post')) {
            $params = $request->request->all();
            $this->value['organizations'][$index]['users'][$indexName]['name'] = $params['userName'];
            $this->value['organizations'][$index]['users'][$indexName]['password'] = $params['userPassword'];
            $yaml = Yaml::dump($this->value);
            file_put_contents('C:\wamp64\www\TestSymfonyService\src/organizations.yaml', $yaml);
            return $this->redirectToRoute('organization',['organization' => $this->value['organizations'][$index]['name']]);
        }
    }
}

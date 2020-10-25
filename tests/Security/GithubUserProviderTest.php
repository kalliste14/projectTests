<?php


namespace App\Tests\Security;


use App\Entity\User;
use App\Security\GithubUserProvider;
use PHPUnit\Framework\TestCase;

class GithubUserProviderTest extends TestCase
{
    protected $client;
    protected $serializer;
    protected $response;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->serializer = $this
            ->getMockBuilder('JMS\Serializer\Serializer')
            ->disableOriginalConstructor()
            ->setMethods(['deserialize'])
            ->getMock();

        $this->response = $this
            ->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->getMock();

        $this->streamedResponse = $this
            ->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->getMock();

        $this->client
            ->expects($this->once()) // Nous nous attendons à ce que la méthode get soit appelée une fois
            ->method('get')
            ->willReturn($this->response)
        ;

        $this->response
            ->expects($this->once()) // Nous nous attendons à ce que la méthode getBody soit appelée une fois
            ->method('getBody')
            ->willReturn($this->streamedResponse);

    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->client = null;
        $this->serializer = null;
        $this->response = null;
        $this->streamedResponse = null;
    }

    public function testLoadUserByUsernameReturningAUser()
    {

        $userData = ['login' => 'a login', 'name' => 'user name', 'email' => 'adress@mail.com', 'avatar_url' => 'url to the avatar', 'html_url' => 'url to profile'];
        $this->serializer
            ->expects($this->once()) // Nous nous attendons à ce que la méthode deserialize soit appelée une fois
            ->method('deserialize')
            ->willReturn($userData);

        $githubUserProvider = new GithubUserProvider($this->client, $this->serializer);
        $userReturn = $githubUserProvider->loadUserByUsername('an-access-token');

        $user = new User($userData['login'],$userData['name'],$userData['email'],$userData['avatar_url'],$userData['html_url']);
        $this->assertEquals($user, $userReturn);
        $this->assertEquals('App\Entity\User', get_class($userReturn));

    }

    public function testLoadUserByUsernameReturningException()
    {
        $userData = [];
        $this->serializer
            ->expects($this->once()) // Nous nous attendons à ce que la méthode deserialize soit appelée une fois
            ->method('deserialize')
            ->willReturn($userData);

        $githubUserProvider = new GithubUserProvider($this->client, $this->serializer);
        $this->expectException('LogicException');

        $userReturn = $githubUserProvider->loadUserByUsername('an-access-token');

    }
}
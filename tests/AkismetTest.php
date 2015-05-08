<?php
use Jrnv\Akismet\AkismetClient;
use Jrnv\Akismet\Post;

class AkismetTest extends \PHPUnit_Framework_TestCase
{
    private $client;
    private $key;
    private $blog;

    public function __construct()
    {
        $config = $this->getConfig();
        $this->key = $config['key'];
        $this->blog = $config['blog'];
    }

    private function getClient($key = null)
    {
        $key = $key ?: $this->key;

        return new \Jrnv\Akismet\Akismet($key, true);
    }

    public function testClientURLGenerator()
    {
        // Make the private method public for testing
        $method = new ReflectionMethod(AkismetClient::class, 'getUrl');
        $method->setAccessible(true);

        $client = new AkismetClient($this->key);

        $verifyUrl = $method->invoke($client, 'verify-key');
        $this->assertEquals(
            'https://rest.akismet.com/1.1/verify-key',
            $verifyUrl,
            'The verify-key url that\'s being generated doesn\'t contain the expected string.'
        );

        $verifyUrl = $method->invoke($client, 'comment-check');
        $this->assertEquals(
            "https://{$this->key}.rest.akismet.com/1.1/comment-check",
            $verifyUrl,
            'The comment-check url that\'s being generated doesn\'t contain the expected string.'
        );
    }

    public function testAPIKeyValidateWithoutKeyParam()
    {
        $this->client = $this->getClient($this->key);
        $result = $this->client->validate($this->blog);

        $this->assertTrue(
            $result,
            'Key is not valid (have you set the correct key, and does it work for the blog you set?'
        );
    }

    public function testPostArraySerialization()
    {
        $post = new Post();
        $post
            ->setUrl('http://jrnv.nl/')
            ->setAuthor('Jeroen Visser')
            ->setAuthorEmail('me@jrnv.nl')
            ->setContent('A beautiful message');

        $expected = [
            'blog'                      => 'http://jrnv.nl/',
            'comment_type'              => 'comment',
            'comment_author'            => 'Jeroen Visser',
            'comment_author_email'      => 'me@jrnv.nl',
            'comment_content'           => 'A beautiful message',
            'comment_date_gmt'          => (new \DateTime)->format('c'),
            'comment_post_modified_gmt' => (new \DateTime)->format('c')
        ];
        $this->assertEquals($expected, $post->toArray(), 'Post serialization is not correct.');
    }

    public function testPostIsSpam()
    {
        $client = $this->getClient();

        $post = new Post();
        $post
            ->setUrl('http://jrnv.nl/')
            ->setUserIp('127.0.0.1')
            ->setAuthor('viagra-test-123');

        $result = $client->check($post);

        $this->assertTrue($result, 'Post with author "viagra-test-123" should be spam, but is not spam.');
    }

    public function testPostIsHam()
    {
        $client = $this->getClient();

        $post = new Post();
        $post
            ->setUrl('http://jrnv.nl/')
            ->setUserIp('127.0.0.1')
            ->setUserRole('administrator');

        $result = $client->check($post);

        $this->assertFalse($result, 'Post with role "administrator" should always be a ham, but was not.');
    }

    private function getConfig()
    {
        if (!file_exists(__DIR__ . '/test.json')) {
            throw new \Exception('File "test.json" not found. Did you copy the dist file?');
        }

        return json_decode(file_get_contents(__DIR__ . '/test.json'));
    }
}

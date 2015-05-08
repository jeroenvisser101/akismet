<?php
namespace Jrnv\Akismet;

use InvalidArgumentException;
use Jrnv\Akismet\Exception\InvalidResponseException;
use Jrnv\Akismet\Exception\NotImplementedException;

class Akismet
{
    /**
     * Akismet API key.
     *
     * @link https://akismet.com/signup
     * @var string
     */
    protected $key = null;

    /**
     * Enables the testing environment.
     *
     * @var bool
     */
    protected $test;

    /**
     * Internal client.
     *
     * @var AkismetClient
     */
    protected $client;

    /**
     * Class constructor.
     *
     * @param string $key  Akismet API key.
     * @param bool   $test Indicates whether or not to use test environment.
     */
    public function __construct($key, $test = false)
    {
        $this->key = $key;
        $this->test = $test;

        $this->client = new AkismetClient($this->key);
    }

    /**
     * Validates the Akismet API key.
     *
     * @param string $blog The front page url of the blog.
     * @param string $key  The Akismet API key. (defaults to instance $key)
     *
     * @return bool
     * @throws InvalidResponseException
     */
    public function validate($blog, $key = null)
    {
        if ($key === null) {
            $key = $this->key;
        }

        $response = $this->client->request('verify-key', [
            'key'  => $key,
            'blog' => $blog
        ]);

        if ((string) $response->getBody() == 'valid') {
            return true;
        } elseif ((string) $response->getBody() == 'invalid') {
            return false;
        } else {
            throw new InvalidResponseException('Response did not contain either valid or invalid.');
        }
    }

    /**
     * Checks a post if it's spam.
     *
     * Returns true if it's spam, false if it's ham.
     *
     * @param Post $post
     *
     * @return bool
     * @throws InvalidResponseException
     * @throws InvalidArgumentException
     */
    public function check(Post $post)
    {
        $data = $post->toArray();
        $response = $this->client->request('comment-check', $data);

        if ((string) $response->getBody() === 'true') {
            return true;
        } else if ((string) $response->getBody() === 'false') {
            return false;
        } else if ((string) $response->getBody() === 'invalid') {
            throw new InvalidArgumentException(sprintf(
                'Request wasn\'t accepted by Akismet. (%s)',
                $response->getHeader('X-akismet-debug-help')
            ));
        } else {
            throw new InvalidResponseException(sprintf(
                'Response did not contain either valid or invalid. (%s)',
                (string) $response->getBody()
            ));
        }
    }

    /**
     * Reports a post for being spam.
     *
     * @param Post $post
     *
     * @throws NotImplementedException
     * @todo this method should be implemented
     */
    public function reportSpam(Post $post)
    {
        throw new NotImplementedException('Method not implemented yet.');
    }

    /**
     * Reports a post for being ham.
     *
     * @param Post $post
     *
     * @throws NotImplementedException
     * @todo This method should be implemented
     */
    public function reportHam(Post $post)
    {
        throw new NotImplementedException('Method not implemented yet.');
    }
}

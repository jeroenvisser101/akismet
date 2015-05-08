<?php
namespace Jrnv\Akismet;

/**
 * This class represents a virtual post on a blog. It's used only to store information.
 *
 * @todo Akismet also accepts many $_SERVER variables, those would increase the chance of catching spam.
 */
class Post
{
    /**
     * The front page or home URL of the instance making the request. For a blog or wiki this would be the front page.
     *
     * Note: Must be a full URI, including http://.
     *
     * This field is required.
     *
     * @var string
     */
    protected $url;

    /**
     * IP address of the comment submitter.
     *
     * This field is required.
     *
     * @var string
     */
    protected $userIp;

    /**
     * User agent string of the web browser submitting the comment - typically the HTTP_USER_AGENT cgi variable. Not to
     * be confused with the user agent of your Akismet library.
     *
     * @var string
     */
    protected $userAgent;

    /**
     * The content of the HTTP_REFERER header should be sent here.
     *
     * @var string
     */
    protected $referer;

    /**
     * The permanent location of the entry the comment was submitted to.
     *
     * @var string
     */
    protected $permalink;

    /**
     * May be blank, comment, trackback, pingback, or a made up value like "registration". It's important to send an
     * appropriate value, and this is further explained
     * {@link http://blog.akismet.com/2012/06/19/pro-tip-tell-us-your-comment_type/ here}.
     *
     * @var string
     */
    protected $type = 'comment';

    /**
     * Name submitted with the comment.
     *
     * @var string
     */
    protected $author;

    /**
     * Email address submitted with the comment.
     *
     * @var string
     */
    protected $authorEmail;

    /**
     * URL submitted with comment.
     *
     * @var string
     */
    protected $authorUrl;

    /**
     * The content that was submitted. Please send the raw text the commenter has entered. HTML and forum tags are
     * accepted.
     *
     * @var string
     */
    protected $content;

    /**
     * The UTC timestamp of the creation of the comment, in ISO 8601 format. May be omitted if the comment is sent to
     * the API at the time it is created.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The UTC timestamp of the publication time for the post, page or thread on which the comment was posted.
     *
     * @var \DateTime
     */
    protected $modifiedAt;

    /**
     * Indicates the language(s) in use on the blog or site, in ISO 639-1 format, comma-separated. A site with articles
     * in English and French might use "en, fr_ca".
     *
     * @var array
     */
    protected $languages = [];

    /**
     * The character encoding for the form values included in comment_* parameters, such as "UTF-8" or "ISO-8859-1".
     *
     * @var string
     */
    protected $charset;

    /**
     * The role of the user that posted this.
     *
     * @var string
     */
    protected $userRole;

    /**
     * Returns a string with keys that Akismet accept.
     *
     * @return array
     * @todo should check if mandatory fields are set.
     */
    public function toArray()
    {
        return array_filter([
            'blog'                      => $this->getUrl(),
            'user_ip'                   => $this->getUserIp(),
            'user_agent'                => $this->getUserAgent(),
            'referrer'                  => $this->getReferer(),
            'permalink'                 => $this->getPermalink(),
            'comment_type'              => $this->getType(),
            'comment_author'            => $this->getAuthor(),
            'comment_author_email'      => $this->getAuthorEmail(),
            'comment_author_url'        => $this->getAuthorUrl(),
            'comment_content'           => $this->getContent(),
            'comment_date_gmt'          => $this->getCreatedAt()->format('c'),
            'comment_post_modified_gmt' => $this->getModifiedAt()->format('c'),
            'blog_lang'                 => implode(', ', $this->getLanguages()),
            'blog_charset'              => $this->getCharset(),
        ]);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * @param string $userIp
     *
     * @return $this
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param string $referer
     *
     * @return $this
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;

        return $this;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     *
     * @return $this
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return $this
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @param string $authorEmail
     *
     * @return $this
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorUrl()
    {
        return $this->authorUrl;
    }

    /**
     * @param string $authorUrl
     *
     * @return $this
     */
    public function setAuthorUrl($authorUrl)
    {
        $this->authorUrl = $authorUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt ?: new \DateTime();
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt ?: new \DateTime();
    }

    /**
     * @param \DateTime $modifiedAt
     *
     * @return $this
     */
    public function setModifiedAt(\DateTime $modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param array $languages
     *
     * @return $this
     */
    public function setLanguages(array $languages)
    {
        $this->languages = $languages;

        return $this;
    }

    /**
     * @param $language
     *
     * @return $this
     */
    public function addLanguage($language)
    {
        $this->languages[] = $language;

        return $this;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     *
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserRole()
    {
        return $this->userRole;
    }

    /**
     * @param string $userRole
     *
     * @return $this
     */
    public function setUserRole($userRole)
    {
        $this->userRole = $userRole;

        return $this;
    }
}

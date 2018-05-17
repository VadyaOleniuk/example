<?php
namespace Clear\LogBundle\Util;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Clear\ContentBundle\Controller\ContentController;

/**
 * Class RequestProcessor
 *
 * @package AppBundle\Util
 */
class RequestProcessor
{
    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RequestProcessor constructor.
     * @param RequestStack $request
     * @param ContainerInterface $container
     */
    public function __construct(RequestStack $request, ContainerInterface $container)
    {
        $this->request = $request;
        $this->container = $container;
    }

    /**
     * @param array $record
     * @return array
     */
    public function processRecord(array $record)
    {
        $req = $this->request->getCurrentRequest();
        $record['extra']['client_ip']       = $req->getClientIp();
        $record['extra']['client_port']     = $req->getPort();
        $record['extra']['uri']             = $req->getUri();
        $record['extra']['query_string']    = $req->getQueryString();
        $record['extra']['method']          = $req->getMethod();
        $record['extra']['request']         = $req->request->all();

        $record['action'] = $this->getAction($record);

        return $record;
    }

    /**
     * @param $record
     * @return string
     */
    protected function getAction($record)
    {
        $action = '';

        switch ($record['message']) {
        case 'content_added':
            $title = '';
            if (isset($record['context']['entity']['content'])) {
                $title = $record['context']['entity']['content']->getTitle();
            }
            $action = "Content with title $title was added";
            break;
        case 'content_updated':
            $title = '';
            if (isset($record['context']['entity']['content'])) {
                $title = $record['context']['entity']['content']->getTitle();
            }
            $action = "Content with title $title was updated";
            break;
        case 'content_deleted':
            $title = '';
            if (isset($record['context']['entity']['content'])) {
                $title = $record['context']['entity']['content']->getTitle();
            }
            $action = "Content with title $title was deleted";
            break;
        case 'content_change_status':
            $status = '';
            if (isset($record['context']['entity']['status'])) {
                switch ($record['context']['entity']['status']) {
                    case ContentController::DRAFT:
                    $status = 'Draft';
                    break;
                    case ContentController::PUBLISHED:
                    $status = 'Published';
                    break;
                    case ContentController::ARCHIVED:
                    $status = 'Archive';
                    break;
                default:
                    $status = 'Published';
                    break;
                }
            }

            $action = "Status was changed to $status";
            break;
        case 'content_added_category':
            $action = "Category was added to content";
            break;
        case 'content_delete_category':
            $action = "Category was deleted from content";
            break;
        case 'content_added_tag':
            $action = "Tag was added to content";
            break;
        case 'content_delete_tag':
            $action = "Tag was deleted from content";
            break;
        default:
            $action = "Unknown message";
            break;
        }

        return $action;
    }
}
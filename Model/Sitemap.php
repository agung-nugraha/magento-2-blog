<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 4/10/2017
 * Time: 11:26 AM
 */
namespace Mageplaza\Blog\Model;

/**
 * Class Sitemap
 * @package Mageplaza\Blog\Model
 */
class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
	/**
	 * @var \Mageplaza\Blog\Helper\Data
	 */
    protected $blogDataHelper;
	/**
	 * @var mixed
	 */
    protected $router;

	/**
	 * Sitemap constructor.
	 * @param \Mageplaza\Blog\Helper\Data $blogDataHelper
	 * @param \Magento\Framework\Model\Context $context
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\Escaper $escaper
	 * @param \Magento\Sitemap\Helper\Data $sitemapData
	 * @param \Magento\Framework\Filesystem $filesystem
	 * @param \Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory $categoryFactory
	 * @param \Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory $productFactory
	 * @param \Magento\Sitemap\Model\ResourceModel\Cms\PageFactory $cmsFactory
	 * @param \Magento\Framework\Stdlib\DateTime\DateTime $modelDate
	 * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @param \Magento\Framework\Stdlib\DateTime $dateTime
	 * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
	 * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
	 * @param array $data
	 */
    public function __construct(
        \Mageplaza\Blog\Helper\Data $blogDataHelper,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Magento\Sitemap\Helper\Data $sitemapData,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Sitemap\Model\ResourceModel\Catalog\CategoryFactory $categoryFactory,
        \Magento\Sitemap\Model\ResourceModel\Catalog\ProductFactory $productFactory,
        \Magento\Sitemap\Model\ResourceModel\Cms\PageFactory $cmsFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $modelDate,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
    
        $this->blogDataHelper=$blogDataHelper;
        $this->router = $this->blogDataHelper->getBlogConfig('general/url_prefix');
        parent::__construct(
            $context,
            $registry,
            $escaper,
            $sitemapData,
            $filesystem,
            $categoryFactory,
            $productFactory,
            $cmsFactory,
            $modelDate,
            $storeManager,
            $request,
            $dateTime,
            $resource,
            $resourceCollection,
            $data
        );
    }
    public function getBlogPostsSiteMapCollection()
    {
        $postCollection=$this->blogDataHelper->postfactory->create()->getCollection();
        $postSiteMapCollection=[];
        if (!$this->router) {
            $this->router = 'blog';
        }
        foreach ($postCollection as $item) {
            if ($item->getEnabled()!=null) :
                $images = null;
                if ($item->getImage()) :
                    $imagesCollection[] = new \Magento\Framework\DataObject(
                        [
                        'url' => $item->getImage(),
                        'caption' => null,
                        ]
                    );
                    $images=new \Magento\Framework\DataObject(['collection'=>$imagesCollection]);
                endif;
                $postSiteMapCollection[$item->getId()]=new \Magento\Framework\DataObject([
                'id'=>$item->getId(),
                'url'=>$this->router.'/post/'.$item->getUrlKey(),
                'images' => $images,
                'updated_at'=>$item->getUpdatedAt(),
                ]);
            endif;
        }
        return $postSiteMapCollection;
    }
    public function getBlogCategoriesSiteMapCollection()
    {
        $categoryCollection=$this->blogDataHelper->categoryfactory->create()->getCollection();
        $categorySiteMapCollection=[];
        foreach ($categoryCollection as $item) {
            if ($item->getEnabled()!=null) :
                $categorySiteMapCollection[$item->getId()]=new \Magento\Framework\DataObject([
                'id'=>$item->getId(),
                'url'=>$this->router.'/category/'.$item->getUrlKey(),
                'updated_at'=>$item->getUpdatedAt(),
                ]);
            endif;
        }
        return $categorySiteMapCollection;
    }
    public function getBlogTagsSiteMapCollection()
    {
        $tagCollection=$this->blogDataHelper->tagfactory->create()->getCollection();
        $tagSiteMapCollection=[];
        foreach ($tagCollection as $item) {
            if ($item->getEnabled()!=null) :
                $tagSiteMapCollection[$item->getId()]=new \Magento\Framework\DataObject([
                    'id'=>$item->getId(),
                    'url'=>$this->router.'/tag/'.$item->getUrlKey(),
                    'updated_at'=>$item->getUpdatedAt(),
                ]);
            endif;
        }
        return $tagSiteMapCollection;
    }
    public function getBlogTopicsSiteMapCollection()
    {
        $topicCollection=$this->blogDataHelper->topicfactory->create()->getCollection();
        $topicSiteMapCollection=[];
        foreach ($topicCollection as $item) {
            if ($item->getEnabled()!=null) :
                $topicSiteMapCollection[$item->getId()]=new \Magento\Framework\DataObject([
                    'id'=>$item->getId(),
                    'url'=>$this->router.'/topic/'.$item->getUrlKey(),
                    'updated_at'=>$item->getUpdatedAt(),
                ]);
            endif;
        }
        return $topicSiteMapCollection;
    }
    public function _initSitemapItems()
    {
        $this->_sitemapItems[] = new \Magento\Framework\DataObject(
            [
                'collection' => $this->getBlogPostsSiteMapCollection(),
            ]
        );
        $this->_sitemapItems[] = new \Magento\Framework\DataObject(
            [
                'collection' => $this->getBlogCategoriesSiteMapCollection(),
            ]
        );
        $this->_sitemapItems[] = new \Magento\Framework\DataObject(
            [
                'collection' => $this->getBlogTagsSiteMapCollection(),
            ]
        );
        $this->_sitemapItems[] = new \Magento\Framework\DataObject(
            [
                'collection' => $this->getBlogTopicsSiteMapCollection(),
            ]
        );
//		die(\Zend_Debug::dump($this->_sitemapItems));
        parent::_initSitemapItems(); // TODO: Change the autogenerated stub
    }
}

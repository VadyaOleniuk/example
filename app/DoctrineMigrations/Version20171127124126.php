<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171127124126 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $content_types = array(
            array('icon' => '/uploads/images/video.svg', 'icon_blue' => '/uploads/images/blue/video.svg', 'icon_black'=> '/uploads/images/blackicon/video.svg', 'icon_dark'=> '/uploads/images/icon/video.svg','icon_ligth' => '/uploads/images/hover/video.svg', 'id' => 7, 'form' => '{"comment":{"title":"Article video","name":"Video {number}: Url and transcript"},"form":{"link":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\UrlType","option":"url","title":"Video URL", "placeholder":"Insert vide link here"},"textarea":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\TextType","option":"","title":"Transcript for video", "placeholder":"Transcript for video"}},"type":"video"}'),
            array('icon' => '/uploads/images/template.svg', 'icon_blue' => '/uploads/images/blue/template.svg', 'icon_black'=> '/uploads/images/blackicon/template.svg', 'icon_dark'=> '/uploads/images/icon/template.svg','icon_ligth' => '/uploads/images/hover/template.svg', 'id' => 8, 'form' => '{"comment":{"title":"Article template","name":"Template {number}: Url and transcript"},"form":{"link":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\UrlType","option":"url","title":"Insert resource URL", "placeholder":"Insert resource link here"},"textarea":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\TextType","option":"", "title": "Transcript for resource", "placeholder":"Transcript for resource"},"file":{"type":"Clear\\\\FileStorageBundle\\\\DataTransformer\\\\AltFileType","option":"file","title":"Upload resource", "placeholder":"Upload resource"}},"type":"resource"}'),
            array('icon' => '/uploads/images/document.svg', 'icon_blue' => '/uploads/images/blue/document.svg', 'icon_black'=> '/uploads/images/blackicon/document.svg', 'icon_dark'=> '/uploads/images/icon/document.svg','icon_ligth' => '/uploads/images/hover/document.svg', 'id' => 9, 'form' => '{"comment":{"title":"Article document","name":"Document {number}: Url and transcript"},"form":{"link":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\UrlType","option":"url","title":"Insert resource URL", "placeholder":"Insert resource link here"},"textarea":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\TextType","option":"", "title": "Transcript for resource", "placeholder":"Transcript for resource"},"file":{"type":"Clear\\\\FileStorageBundle\\\\DataTransformer\\\\AltFileType","option":"file","title":"Upload resource", "placeholder":"Upload resource"}},"type":"resource"}'),
            array('icon' => '/uploads/images/screenText.svg', 'icon_blue' => '/uploads/images/blue/screenText.svg', 'icon_black'=> '/uploads/images/blackicon/screenText.svg', 'icon_dark'=> '/uploads/images/icon/screenText.svg','icon_ligth' => '/uploads/images/hover/screenText.svg', 'id' => 10, 'form' => ''),
            array('icon' => '/uploads/images/caseStudy.svg', 'icon_blue' => '/uploads/images/blue/caseStudy.svg', 'icon_black'=> '/uploads/images/blackicon/caseStudy.svg', 'icon_dark'=> '/uploads/images/icon/caseStudy.svg','icon_ligth' => '/uploads/images/hover/caseStudy.svg', 'id' => 11, 'form' => ''),
        );
        foreach ($content_types as $content_type) {
            $this->addSql('UPDATE content_type SET icon = :icon, icon_blue = :icon_blue, icon_black = :icon_black, icon_dark = :icon_dark, icon_ligth = :icon_ligth, form = :form WHERE id = :id', $content_type);
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}

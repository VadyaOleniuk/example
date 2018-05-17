# Clear CMS Rest Backend
`To start the system you need to create in the user's database with the name 'Clear Assured' and the surname one calls 'Clear Assured'`
#Need add param 
##File parameters.yml
*    user_not_delete: 'Clear'

#Need update database with 15.11.2017
    UPDATE  `content_type` SET  `form` = '{"comment":{"title":"Article document","name":"Document {number}: Url and transcript"},"form":{"link":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\UrlType","option":"url","title":"Insert resource URL", "placeholder":"Insert resource link here"},"textarea":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\TextType","option":"textarea", "title": "Transcript for resource", "placeholder":"Transcript for resource"},"file":{"type":"Clear\\\\ContentBundle\\\\Form\\\\DataTransformer\\\\AltFileType","option":"file","title":"Upload resource", "placeholder":"Upload resource"}},"type":"resource"}' WHERE  `content_type`.`id` =9;  
  
    UPDATE `content_type` SET  `form` = '{"comment":{"title":"Article template","name":"Template {number}: Url and transcript"},"form":{"link":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\UrlType","options":"url","title":"Insert resource URL", "placeholder":"Insert resource link here"},"textarea":{"type":"Symfony\\\\Component\\\\Form\\\\Extension\\\\Core\\\\Type\\\\TextType","options":"textarea", "title": "Transcript for resource", "placeholder":"Transcript for resource"},"file":{"type":"Clear\\\\ContentBundle\\\\Form\\\\DataTransformer\\\\AltFileType","options":"file","title":"Upload resource", "placeholder":"Upload resource"}},"type":"resource"}' WHERE  `content_type`.`id` =8;
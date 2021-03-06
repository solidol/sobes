select 
    `document_donestr`.`document_id` AS `document_id`,
    substring_index(substring_index(`document_donestr`.`value`,'";',6),':"',-(1)) AS `donetext` 
  from 
    `document_donestr` 
  where 
    (substring_index(substring_index(`document_donestr`.`value`,'";',6),':"',-(1)) > '') 
  order by 
    `document_donestr`.`id` desc
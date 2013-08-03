
# Payments in
INSERT INTO transaction  (date_id,payment_id,comment,amount,active,created)  
SELECT `date`.id, paymentsIn.payment_id, paymentsIn.comment,  paymentsIn.amount, 
paymentsIn.active, paymentsIn.created  
FROM paymentsIn LEFT JOIN `date` on paymentsIn.`date`=`date`.`date`;

# payments out
INSERT INTO transaction  (date_id,payment_id,comment,amount,active,created)  
SELECT `date`.id, paymentsOut.payment_id, paymentsOut.comment, CONCAT('-',paymentsOut.amount), 
paymentsOut.active, paymentsOut.created  
FROM paymentsOut LEFT JOIN `date` on paymentsOut.`date`=`date`.`date`;

# trim off broken (everything pre August)
DELETE FROM transaction WHERE date_id IN (select date.id FROM date where date.date < '2012-08-31');
DELETE FROM date where date.date < '2012-08-31';
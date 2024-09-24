select x.Id,
x.SubscriberKey,
x.EmailAddress,
x.FirstName,
x.LanguageCode,
x.ActiveLoyalty,
x.RedeemablePoints,
x.Account_Created,
x.Status,
x.MostRecentClickDate from (SELECT 
DISTINCT 
up.Id,
s.SubscriberKey,
s.EmailAddress,
s.FirstName,
s.LanguageCode,
p.ActiveLoyalty,
up.RedeemablePoints,
'TRUE' AS Account_Created,
'AH' AS Status,
c.EventDate AS MostRecentClickDate,
   ROW_NUMBER() OVER (
      PARTITION BY s.SubscriberKey
      ORDER BY c.EventDate desc
   ) row_num
FROM  ent.CT_USER_PROFILE up 
INNER JOIN ent.PP_MasterProfile_ActiveNewsletter_base s ON s.emailaddress = up.emailaddress
LEFT JOIN ent.PP_MasterProfile p ON s.SubscriberKey = p.SubscriberKey
JOin ent.PNA__MostRecentClick c on c.subscriberkey =s.SubscriberKey
WHERE up.dateCreated >= '2023-06-15'
AND c.clientid='100022221'
AND  c.EventDate <= DATEADD(day, -30, GETDATE())
and (s.LanguageCode LIKE '%EN%' or s.LanguageCode is null or s.LanguageCode ='null' or s.LanguageCode ='')
and s.subscriberkey not in (select subscriberkey from ent._BusinessUnitUnsubscribes where BusinessUnitID='100022221')
and s.subscriberkey not in (select ma.subscriberkey from ent.PTR_US_Re_Engagement_AH ma  ))x
where x.row_num=1
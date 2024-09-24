SELECT distinct
        mp.SubscriberKey
        , mp.EmailAddress
        , mp.FirstName
        , mp.LanguageCode
        ,'S' AS Status
        , CASE
          WHEN mp.ActiveLoyalty IS NULL THEN 'PTR'
          ELSE mp.ActiveLoyalty
          END AS ActiveLoyalty
    FROM ent.pp_masterprofile mp
    JOIN ent.pp_master_subscription ms ON ms.subscriberkey = mp.subscriberkey
    JOIN 
    (
      SELECT 
        SubscriberKey, 
        EventDate,
        ClientID,
        ROW_NUMBER() OVER(PARTITION BY SubscriberKey ORDER BY EventDate DESC) num
      FROM ent.PNA__MostRecentClick
      WHERE EventDate <= DATEADD(day, -30, GETDATE())
    ) c
    on c.subscriberkey = ms.subscriberkey
    JOIN ent.PP_MasterProfile_ActiveNewsletter_base nw
    ON mp.SubscriberKey = nw.SubscriberKey
    WHERE ms.subscriptionid = '100022221_PTR_NEWSLETTER_PEPSICOTRNEWS_20200819'
    AND ms.subscriptionstatus = 'true'
    AND c.num = 1
    AND c.clientid='100022221'
    and (mp.LanguageCode LIKE '%EN%' or mp.LanguageCode is null or mp.LanguageCode ='null' or mp.LanguageCode ='')
    AND mp.emailaddress NOT IN (SELECT emailaddress FROM ent.PTR_US_AH_Post_Launch)
    AND mp.emailaddress NOT IN (SELECT emailaddress FROM ent.PTR_US_Users_20230512) 
    AND	mp.emailaddress NOT IN (SELECT emailaddress FROM ent.CT_USER_PROFILE)
    AND mp.emailaddress NOT IN (SELECT emailaddress FROM ent.ALL_Snacks_Account_Holders)
    AND mp.subscriberkey not in (select sma.subscriberkey from ent.PTR_US_Re_Engagement_S sma  )
    and mp.subscriberkey not in (select subscriberkey from ent._BusinessUnitUnsubscribes where BusinessUnitID='100022221')

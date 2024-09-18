<!-- Ampscript Code start 
  %%[
         
        /* ### Sweepstakes AMPScript ### */

        /* Auxiliary variables */
        set @DE_Sweeps = "ent.Sweepstakes_Data_EN"
        set @activeLoyalty = Lookup("ent.PTR - Re-engagement Journey - EN QA_PTR - Re-engagem_1sgDo000000008lIAA_85RDo000000007DMAQ_I", "Brand_Affinity", "emailAddress", emailAddress)
        set @all_sweeps = LookUpOrderedRows(@DE_Sweeps, 0, "SweepID asc", "Enabled", "TRUE")
        set @sweeps_number = RowCount(@all_sweeps)
        set @regex = concat("(",@activeLoyalty,")") /* For testing brand eligibility */        
        set @number_of_exclusive_sweeps = RowCount(LookUpRows(@DE_Sweeps, "EligibleBrands", @activeLoyalty))
        
        /* Business requirement: exclusive sweeps should shown first. Then, the (high-value) shared sweeps. */
        /* If there are two or more exclusive sweeps, the first two are chosen */
        IF @number_of_exclusive_sweeps >= 2 THEN
          set @exclusive_sweeps = LookUpRows(@DE_Sweeps, "EligibleBrands", @activeLoyalty)

          set @sweeps1image = Field(Row(@exclusive_sweeps, 1), "ImageURL")
          set @sweeps1link = Field(Row(@exclusive_sweeps, 1), "Link")
          set @sweeps1headline = Field(Row(@exclusive_sweeps, 1), "Headline")
          set @sweeps1legal = Field(Row(@exclusive_sweeps, 1), "Legal")

          set @sweeps2image = Field(Row(@exclusive_sweeps, 2), "ImageURL")
          set @sweeps2link = Field(Row(@exclusive_sweeps, 2), "Link")
          set @sweeps2headline = Field(Row(@exclusive_sweeps, 2), "Headline")
          set @sweeps2legal = Field(Row(@exclusive_sweeps, 2), "Legal")
         
        /* If there's only one exclusive sweep, show it then show the first shared sweep */
        ELSEIF @number_of_exclusive_sweeps == 1 THEN
          set @sweeps1image = Lookup(@DE_Sweeps, "ImageURL", "EligibleBrands", @activeLoyalty)
          set @sweeps1link = Lookup(@DE_Sweeps, "Link", "EligibleBrands", @activeLoyalty)
          set @sweeps1headline = Lookup(@DE_Sweeps, "Headline", "EligibleBrands", @activeLoyalty)
          set @sweeps1legal = Lookup(@DE_Sweeps, "Legal", "EligibleBrands", @activeLoyalty)
          set @sweeps1ID = Lookup(@DE_Sweeps, "SweepID", "EligibleBrands", @activeLoyalty)          
          
          FOR @i = 1 TO @sweeps_number DO
           set @eligibleBrands = Field(Row(@all_sweeps, @i), "EligibleBrands")
           set @isNotEligible = Empty(RegExMatch(@eligibleBrands, @regex, 0))
           set @sweep_ID = Field(Row(@all_sweeps, @i), "SweepID")
           
           IF NOT @isNotEligible AND @sweepID != @sweeps1ID AND EMPTY(@sweeps2image) THEN
            set @sweeps2image = Field(Row(@all_sweeps, @i), "ImageURL")
            set @sweeps2link = Field(Row(@all_sweeps, @i), "Link")
            set @sweeps2headline = Field(Row(@all_sweeps, @i), "Headline")
            set @sweeps2legal = Field(Row(@all_sweeps, @i), "Legal")            
           ENDIF           
           
          NEXT @i
        /* Else show only shared sweeps */
        ELSE
         FOR @i = 1 TO @sweeps_number DO
          set @eligibleBrands = Field(Row(@all_sweeps, @i), "EligibleBrands")
          set @isNotEligible = Empty(RegExMatch(@eligibleBrands, @regex, 0))
          
          IF NOT @isNotEligible AND EMPTY(@sweeps1image) THEN
            set @sweeps1image = Field(Row(@all_sweeps, @i), "ImageURL")
            set @sweeps1link = Field(Row(@all_sweeps, @i), "Link")
            set @sweeps1headline = Field(Row(@all_sweeps, @i), "Headline")
            set @sweeps1legal = Field(Row(@all_sweeps, @i), "Legal")           
            
          ELSEIF NOT @isNotEligible AND EMPTY(@sweeps2image) AND NOT EMPTY(@sweeps1image) THEN
            set @sweeps2image = Field(Row(@all_sweeps, @i), "ImageURL")
            set @sweeps2link = Field(Row(@all_sweeps, @i), "Link")
            set @sweeps2headline = Field(Row(@all_sweeps, @i), "Headline")
            set @sweeps2legal = Field(Row(@all_sweeps, @i), "Legal") 
          ENDIF
         NEXT @i
        ENDIF
        
        /* Verifies that the two sweeps are able to render. Otherwise abort send for subscriber */
        IF EMPTY(@sweeps1image) OR EMPTY(@sweeps2image) THEN
         RaiseError("Insufficient sweepstakes to send to subscriber", TRUE)
        ENDIF

        set @firstName = FirstName
        IF empty(@firstName) OR @firstName == "FIRSTNAME" THEN
            set @memberName = "Valued Member"
            set @ribbon = 'Enter now for your chance to win!'
        ELSE
            set @ribbon = concat(ProperCase(@firstName), ', enter now for your chance to win!')
            set @memberName = ProperCase(@firstName)
        ENDIF
        
        
        set @sub_loyalty = Lookup("ent.PTR - Re-engagement Journey - EN QA_PTR - Re-engagem_1sgDo000000008lIAA_85RDo000000007DMAQ_I", "Brand_Affinity", "emailAddress", emailAddress)
        /* Active Loyalty */
  IF @sub_loyalty != "PTR" THEN
  set @brand_header_img = Lookup("PTR_Modular_Brands_Attributes", "Header Image URL", "Brand Short Name", @sub_loyalty)
  set @brand_header_link = Lookup("PTR_Modular_Brands_Attributes", "Header Link", "Brand Short Name", @sub_loyalty)
  set @brand_header_alt_text = Lookup("PTR_Modular_Brands_Attributes", "Header Alt Text", "Brand Short Name", @sub_loyalty)
  ENDIF
  ]%%

 Ampscript Code End-->
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
 <head>
   <!--[if gte mso 9]>
 <xml>
 <o:OfficeDocumentSettings>
 <o:AllowPNG/>
 <o:PixelsPerInch>96</o:PixelsPerInch>
 </o:OfficeDocumentSettings>
 </xml>
 <![endif]-->
 <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 <meta name="format-detection" content="date=no" />
 <meta name="format-detection" content="address=no" />
 <meta name="format-detection" content="telephone=no" />
 <meta name="x-apple-disable-message-reformatting" />
 <meta name="color-scheme" content="light dark">
 <meta name="supported-color-schemes" content="light dark">
 <!-- Loading Custom Fonts -->
 <!--[if !mso]><!-->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;700&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet" />
 <!--<![endif]-->
 <!-- END Loading Custom Fonts -->
 <!--[if gte mso 9]>
 <style type="text/css" media="all">
 sup { font-size: 100% !important; }
 </style>
 <![endif]-->
 <!-- body, html, table, thead, tbody, tr, td, div, h1, h2, h3, h4, h5, p, a, span, em, i, strong { font-family: Arial, sans-serif !important; } -->
 <title>Win like a pro while you snack like a champion 🙌</title>
 <!--[if (gte mso 9)|(IE)]><!-->
     <style>
         @import url("https://fonts.googleapis.com/css?family=Montserrat:400,500,700%7COpen+Sans:400,700&display=swap");
         @import url("https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&family=Montserrat:wght@200;300;400;500;600;700&display=swap");
         
         /* PepsiCo Fonts */
         @font-face {
         font-family: "GTWalsheim";
         font-style: normal;
         font-weight: 400;
         display: swap;
         src: url(https://www.tastyrewards.com/themes/tastytheme/src/fonts/gtwalsheimbold-webfont.woff2)
         format("woff2");
         }
         
         @font-face {
         font-family: "GTWalsheim";
         font-style: normal;
         font-weight: 700;
         display: swap;
         src: url(https://www.tastyrewards.com/themes/brandstheme/src/fonts/GTWalsheim/GTWalsheim-Black.woff2)
         format("woff2");
         }
         
         @font-face {
         font-family: "Akzidenz Grotesk";
         font-style: normal;
         font-weight: 400;
         display: swap;
         src: url(https://www.tastyrewards.com/themes/tastytheme/src/fonts/newAkzidenz.woff2)
                 format("woff2");
         }
         
         /* Bold font */
         @font-face {
         font-family: "Akzidenz Grotesk";
         font-style: normal;
         font-weight: 700;
         display: swap;
         src: url(https://www.tastyrewards.com/themes/brandstheme/src/fonts/AkzidenzGrotesk/AkzidenzGrotesk-Bold.woff2)
                 format("woff2");
         }
         </style>
         <!--<![endif]-->
         
 
 <style type="text/css" media="screen">
 body { padding:0 !important; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#fffffe; -webkit-text-size-adjust:none }
 a { color:#333333; text-decoration:none }
 p { margin:0 !important }
 strong { font-weight: bold !important; }
 h1,
 h2,
 h3,
 h4,
 h5,
 h6,
 p,
 ul,
 li { padding: 0 !important; margin: 0 !important; mso-line-height-rule:exactly; Margin: 0; }
 ul { list-style-position: inside !important; }
 .text-preview { display: none !important; }
 a.skip-main { left:-999px; position:absolute; top:auto; width:1px; height:1px; overflow:hidden; z-index:-999; }
 a.skip-main:focus, a.skip-main:active { color: #ffffff; background-color:#ffffff; left: auto; top: auto; width: 30%; height: auto; overflow:auto; margin: 10px 35%; padding:5px; border-radius: 15px; border:4px solid yellow; text-align:center; font-size:12em; z-index:999; }
 table { mso-table-lspace:0pt; mso-table-rspace:0pt; }
 img { margin: 0 !important; -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */ }
 img, a img{ border:0; outline:none; text-decoration:none; }
 #outlook a { padding:0; }
 .ReadMsgBody { width:100%; }
 .ExternalClass { width:100%; }
 div,p,a,li,td,blockquote { mso-line-height-rule:exactly; }
 a[href^=tel],a[href^=sms] { color:inherit; text-decoration:none; }
 .ExternalClass, .ExternalClass p, .ExternalClass td, .ExternalClass div, .ExternalClass span, .ExternalClass font { line-height:100%; }
 a[x-apple-data-detectors] { color:inherit !important; text-decoration:none !important; font-size:inherit !important; font-family:inherit !important; font-weight:inherit !important; line-height:inherit !important; }
 #MessageViewBody, #MessageWebViewDiv { width: 100% !important; } /* Samsung Galaxy Note 4 Samsung Mail - make email stay centered */
 .dijitReset { color: #000001 !important; }
 .btn a { display: block; text-decoration: none; }
 .btnp-6-15 a { padding: 6px 15px; }
 .btnp-18-25 a { padding: 18px 25px; }
 .l-white a { color: #ffffff; }
 .l-dark a { color: #333333; }
 .l-grey a { color: #3d3c3c; }
 .l-blue a { color: #0e4caa; }
 .l-red a { color: #de202c; }
 .br-12-12-0-0 { border-radius: 12px 12px 0 0; }
 :root {
 color-scheme: light dark;
 supported-color-schemes: light dark;
 }
 @media (prefers-color-scheme: dark) {
 .bg-dark { background: #000000 !important; }
 .bg-white { background: #fefefe !important; }
 .bg-grey { background: #f8f8f8 !important; }
 .bg-red { background: #de202c !important; }
 .bg-blue { background: #0d4ca9 !important; }
 .c-white { color: #ffffff !important; }
 .c-dark { color: #333333 !important; }
 .c-blue { color: #0e4caa !important; }
 .c-red { color: #de202c !important; }
 .c-grey { color: #3d3c3c !important; }
 .l-white a { color: #ffffff !important; }
 .l-dark a { color: #333333 !important; }
 .l-blue a { color: #0e4caa !important; }
 .l-red a { color: #de202c !important; }
 .l-grey a { color: #3d3c3c !important; }
 }
 /*Add this styling for support in Outlook app (Android).*/
 [data-ogsc] .bg-dark { background: #000000 !important; }
 [data-ogsc] .bg-white { background: #fefefe !important; }
 [data-ogsc] .bg-grey { background: #f8f8f8 !important; }
 [data-ogsc] .bg-red { background: #de202c !important; }
 [data-ogsc] .bg-blue { background: #0d4ca9 !important; }
 [data-ogsc] .c-white { color: #ffffff !important; }
 [data-ogsc] .c-dark { color: #333333 !important; }
 [data-ogsc] .c-blue { color: #0e4caa !important; }
 [data-ogsc] .c-red { color: #de202c !important; }
 [data-ogsc] .c-grey { color: #3d3c3c !important; }
 [data-ogsc] .l-white a { color: #ffffff !important; }
 [data-ogsc] .l-dark a { color: #333333 !important; }
 [data-ogsc] .l-blue a { color: #0e4caa !important; }
 [data-ogsc] .l-red a { color: #de202c !important; }
 [data-ogsc] .l-grey a { color: #3d3c3c !important; }
 /* Mobile styles */
 @media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
    .mpx-15 { padding-left: 15px !important; padding-right: 15px !important; }
 
    .mpy-20 { padding-top: 20px !important; padding-bottom: 20px !important; }
 
    .mpb-20 { padding-bottom: 20px !important; }
 
 .td,
 .m-shell { width: 100% !important; min-width: 100% !important; }
 .mt-left { text-align: left !important; }
 .mt-center { text-align: center !important; }
 .me-left { margin-right: auto !important; }
 .me-center { margin: 0 auto !important; }
 .mh-auto { height: auto !important; }
 .mw-auto { width: auto !important; }
 .fluid-img img { width: 100% !important; max-width: 100% !important; height: auto !important; }
 .column,
 .column-top,
 .column-dir,
 .column-dir-top { float: left !important; width: 100% !important; display: block !important; }
 .m-hide { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }
 .m-block { display: block !important; }
 .mw-15 { width: 15px !important; }
 .mw-100p { width: 100% !important; }
 .mw-60 { width: 60px !important; height: auto !important; }
 .mw-60 img { width: 60px !important; height: auto !important; }
 .mt-28 { margin-top: -28px !important; }
 .mh-0 { height: auto !important; }
 .mh-20 { height: 20px !important; }
 .mh-54 { height: 54px !important; }
 }
 
 @media only screen and (max-device-width: 624px), only screen and (max-width: 624px) {
 .mb-cta-text { font-size: 25px!important; }
 .mb-padding-x { padding-left: 10px !important;
                 padding-right: 10px !important; }
 .mb-padding-points { padding-left: 0 !important;
                      padding-right: 12px !important; }
 .font-fix { font-size: 20px !important;
             font-weight: 600 !important; }
 }
 
 </style>
 </head>
 <body class="body" style="padding:0 !important; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#fffffe; -webkit-text-size-adjust:none;">
 <center>
 <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0; padding: 0; width: 100%; height: 100%;" bgcolor="#fffffe" class="bg-dark">
 <tr>
 <td style="margin: 0; padding: 0; width: 100%; height: 100%;" align="center" valign="top">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <!-- Preview Text -->
 <tr class="m-hide" style="mso-hide: all;">
 <td style="font-size: 0pt; line-height: 0pt;">
 <!--*|IF:MC_PREVIEW_TEXT|*-->
 <!--<![endif]-->
 <!--*|END:IF|*-->
 </td>
 </tr>
 <tr class="m-hide" style="mso-hide: all;">
 <td style="font-size: 0pt; line-height: 0pt;">&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;</td>
 </tr>      <!-- END Preview Text -->
 </table>
 <table width="640" border="0" cellspacing="0" cellpadding="0" class="m-shell">
 <tr>
 <td class="td" style="width:640px; min-width:640px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
 <!-- HEADER START -->
 
          <!-- LOGO START -->
          %%[ 
          set @points = Lookup("ent.CT_User_Profile", "redeemablePoints", "emailAddress", emailAddress)
          ]%%
 
          %%[ IF NOT EMPTY(@points) AND @points != 0 THEN 
          set @asterisk = "*Log in to your Account Dashboard to confirm the actual number of Entries you have to use to enter into a Sweepstakes. Recent activity may not be reflected in the Entries shown above and your actual Entries available may be different."
          set @disclaimer = "Unused Entries will expire after 12 months of no activity in your Account or 12 months following the date on which you completed an Activity, earned an Entry but did not use for a drawing (any unused Entries remaining in an Account after that time will be forfeited)."
          ]%%
          <center>
           <!--[if (gte mso 9)|(IE)]>
                                 <table width="640" style="width: 640px;" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                 <tr>
                                 <td width="640" style="background-color:#0057A2; text-align: center;">
                            <!
                       [endif]--><!-- PERSONALIZATION RIBBON START --><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 640px; width: 100%; text-align: center">
            
             <tr>
              <td>
               <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #0057a2; text-align: center">
                
                 <tr>
                  <td class="bg-blue l-white a" width="640">
                  </td><td class="bg-blue l-white a" style="
                                     width: 100%;
                                     color: #ffffff;
                                     font-family: 'Akzidenz Grotesk', 'Montserrat', sans-serif,
                                       Helvetica, Arial;
                                     font-size: 12px;
                                     line-height: 130%;
                                     padding-top: 16px;
                                     padding-bottom: 4px;
                                   ">
                   %%=v(@ribbon)=%%</td></tr><tr>
                  <td class="bg-blue l-white a" width="640">
                  </td><td class="bg-blue l-white a" style="
                                     width: 100%;
                                     color: #ffffff;
                                     font-family: 'Akzidenz Grotesk', 'Montserrat', sans-serif,
                                       Helvetica, Arial;
                                     font-size: 12px;
                                     font-weight: 700;
                                     padding-bottom: 14px;
                                   ">
                   <a alias="view_in_browser_header" href="%%view_email_url%%" style="color: #ffffff; text-decoration: none" target="_blank">View in browser</a></td></tr></table></td></tr></table><!-- PERSONALIZATION RIBBON END -->
                   <!-- LOGO START --><!--[if (gte mso 9)|(IE)]->
                              <table cellpadding="0" cellspacing="0" border="0" width="640">
                                <tr>
                                  <td width="640" height="151" style="">                
                                     <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:640px;height:151px;">
                                     <v:fill type="frame" src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/6e7f0f29-d87c-45be-8243-006faaa587be.png" />
                                     <v:textbox inset="0,0,0,0">               
                            <![endif]--><table background="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/6e7f0f29-d87c-45be-8243-006faaa587be.png" border="0" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 640px; width: 100%; text-align: center; background-size: cover;">
            
             <tr>
              <td style="padding: 0; text-align: left">
               <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="display: inline-block">
                
                 <tr>
                  <td height="151" style="padding: 0; text-align: center; vertical-align: center;" width="201">
                   <a alias="logo_header" conversion="false" data-linkto="https://" href="https://www.tastyrewards.com/en-us/" target="_blank" title=""><img alt="PepsiCo Tasty Rewards" height="103.27" src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/5711fd2b-69e0-400d-9489-b668ba2419af.png" style="width: 113.59px; height: 103.27px; padding: 0px; text-align: center;" width="113.59"></a></td><td height="151" style="border-right: 0.5px solid #ffffff; opacity: 30%">
                  </td><td width="20">
                  </td><td class="font-fix l-white a" style="
                                     font-family: 'GTWalsheim', 'Arimo', sans-serif, Helvetica,
                                       Arial;
                                     font-size: 30px;
                                     font-weight: 700;
                                     color: #ffffff;
                                     mso-line-height-rule: exactly;
                                     line-height: 27.3px;
                                     text-align: left;
                                   " width="204">
                   Your Entries* Available</td><td width="5">
                  </td><td style="
                                     font-family: 'GTWalsheim', 'Arimo', sans-serif, Helvetica,
                                       Arial;
                                     font-size: 26px;
                                     font-weight: 700;
                                     color: #ffffff;
                                     mso-line-height-rule: exactly;
                                     line-height: 27.3px;
                                   " width="149">
                   <!--[if (gte mso 9)|(IE)]->
                                 <table cellpadding="0" cellspacing="0" border="0" role="presentation">
                                 <tr>
                                 <td style="vertical-align: center; background-color:#f8f8f8;">
                                 <![endif]--><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #f8f8f8; border-radius: 8px 8px 8px 8px; background-size: cover;">
                    
                     <tr>
                      <td class="mb-cta-text mb-padding-x" style="
                                           color: #0057a2;
                                           font-size: 32px;
                                           font-weight: 700;
                                           padding: 20.3px 8.17px 25.3px 34px;
                                           border-radius: 8px 0 0 8px; 
                                         ">
                       <img height="28.59" src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/689f2f60-5e07-44cc-a09b-656ee5891379.png" width="28.59"></td>
                      <td class="mb-cta-text mb-padding-points" style="
                                           color: #0057a2;
                                           font-family: 'GTWalsheim', 'Arimo', sans-serif, Helvetica,
                                       Arial;
                                           font-size: 35px;
                                           font-weight: 700;
                                           padding: 20.3px 40px 25.3px 0;
                                           border-radius: 0 8px 8px 0;
                                         ">
                       %%=v(Replace(FormatNumber(Field(Row(@JSONrows, @PointsBalancenumber),1), 'N0', 'en-us'), ',', ''))=%%</td></tr></table><!--[if (gte mso 9)|(IE)]->
                                   </td>
                                   </tr>
                                   </table>
                                   <![endif]--></td></tr></table></td></tr></table><!--[if (gte mso 9)|(IE)]->
                                 </v:textbox>
                               </v:rect>
                             </td>
                           </tr>
                         </table>
                       <![endif]--><!-- LOGO END --><!--[if (gte mso 9)|(IE)]>
                             </td>
                             </tr>
                             </table>
                             <![endif]--></center>
          %%[ ENDIF ]%%
 
 
          %%[ IF NOT EMPTY(@points) AND @points == 0 THEN 
          set @asterisk = "*Log in to your Account Dashboard to confirm the actual number of Entries you have to use to enter into a Sweepstakes. Recent activity may not be reflected in the Entries shown above and your actual Entries available may be different."
          set @disclaimer = "Unused Entries will expire after 12 months of no activity in your Account or 12 months following the date on which you completed an Activity, earned an Entry but did not use for a drawing (any unused Entries remaining in an Account after that time will be forfeited)."
          ]%%
          <center>    
           <!--[if (gte mso 9)|(IE)]>
                                 <table width="640" style="width: 640px;" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                 <tr>
                                 <td width="640" style="background-color:#0057A2; text-align: center;">
                            <![endif]--><!-- PERSONALIZATION RIBBON START --><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 640px; width: 100%; text-align: center">
            
                           <tr>
                               <td>
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #0057a2; text-align: center">
                                 
                                  <tr>
                                   <td class="bg-blue l-white a" width="640">
                                   </td><td class="bg-blue l-white a" style="
                                                      width: 100%;
                                                      color: #ffffff;
                                                      font-family: 'Akzidenz Grotesk', 'Montserrat', sans-serif,
                                                        Helvetica, Arial;
                                                      font-size: 12px;
                                                      line-height: 130%;
                                                      padding-top: 16px;
                                                      padding-bottom: 4px;
                                                    ">
                                    %%=v(@ribbon)=%%</td></tr><tr>
                                   <td class="bg-blue l-white a" width="640">
                                   </td><td class="bg-blue l-white a" style="
                                                      width: 100%;
                                                      color: #ffffff;
                                                      font-family: 'Akzidenz Grotesk', 'Montserrat', sans-serif,
                                                        Helvetica, Arial;
                                                      font-size: 12px;
                                                      font-weight: 700;
                                                      padding-bottom: 14px;
                                                    ">
                                    <a alias="view_in_browser_header" href="%%view_email_url%%" style="color: #ffffff; text-decoration: none" target="_blank">View in browser</a></td></tr></table></td></tr></table><!-- PERSONALIZATION RIBBON END -->
       <!--[if (gte mso 9)|(IE)]->
                           <table cellpadding="0" cellspacing="0" border="0" width="640">
                             <tr>
                               <td width="640" height="151" style="">                
                                  <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:640px;height:151px;">
                                  <v:fill type="frame" src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/6e7f0f29-d87c-45be-8243-006faaa587be.png" />
                                  <v:textbox inset="0,0,0,0">               
                         <![endif]--><table background="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/6e7f0f29-d87c-45be-8243-006faaa587be.png" border="0" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 640px; width: 100%; text-align: center; background-size: cover;">
         
                           <tr>
                               <td style="padding: 0; text-align: left">          
                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="display: inline-block">
                                   <tr>
                                       <td height="151" style="padding: 0; text-align: center; vertical-align: center;" width="201">
                                           <a alias="logo_header" conversion="false" data-linkto="https://" href="https://www.tastyrewards.com/en-us/" target="_blank" title=""><img alt="PepsiCo Tasty Rewards" height="103.27" src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/3/5711fd2b-69e0-400d-9489-b668ba2419af.png" style="width: 113.59px; height: 103.27px; padding: 0px; text-align: center;" width="113.59"></a>
                                       </td>
                                       <td height="151" style="border-right: 0.5px solid #ffffff; opacity: 30%">
                                       </td>
                                       <td width="20">
                                       </td>
                                       <a alias="header_link" conversion="false" data-linkto="https://" href="https://www.tastyrewards.com/en-us/" target="_blank" title="">
                                       <td class="l-white a mb-cta-text" style="font-family: 'GTWalsheim', 'Arimo', sans-serif, Helvetica, Arial;
                                                                                   font-size: 30px; font-weight: 700; color: #ffffff;
                                                                                   mso-line-height-rule: exactly; line-height: 28px;
                                                                                   text-align: center;" width="400">
                                                      Out of Entries?<br>
                                                      <!--[if (gte mso 9)|(IE)]->
                 <style type="text/css" media="all"
                 class="link l-white" <a href="https://www.tastyrewards.com/en-us/" target="_blank" alias="" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">Earn more now!&nbsp;</span></a></style></td>
                              </tr>
                              </table>
                              <![endif]-->
                                                      <a class="l-white a" alias="header_link" conversion="false" data-linkto="https://" href="https://www.tastyrewards.com/en-us/" target="_blank" title="" style="text-decoration: none; color: #ffffff !important;">
                                                       Earn more now!</a>
                                                       <!--[if (gte mso 9)|(IE)]>
                   </td>
                   </tr>
                   </table>
                   <![endif]-->   
                                                                                                   
                                       </td>
                                       </a>
                                       <td width="5">
                                       </td>
                                   </tr>
                               </table>
                           </td>
                       </tr>
                       </table><!--[if (gte mso 9)|(IE)]->
                       </v:textbox>
                     </v:rect>
                   </td>
                 </tr>
               </table>
             <![endif]--><!-- LOGO END --><!--[if (gte mso 9)|(IE)]>
                   </td>
                   </tr>
                   </table>
                   <![endif]-->
          </center>
   
          %%[ ENDIF ]%%
 
          %%[ IF EMPTY(@points) THEN ]%%
           <center>
          <!-- Logo -->
                    <!--[if (gte mso 9)|(IE)]>
                                 <table width="640" style="width: 640px;" cellpadding="0" cellspacing="0" border="0" role="presentation">
                                 <tr>
                                 <td width="640" style="background-color:#0057A2; text-align: center;">
                            <!
                       [endif]--><!-- PERSONALIZATION RIBBON START --><table border="0" cellpadding="0" cellspacing="0" role="presentation" style="max-width: 640px; width: 100%; text-align: center">
            
                         <tr>
                           <td>
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="background-color: #0057a2; text-align: center">
                             
                              <tr>
                               <td class="bg-blue l-white a" width="640">
                               </td><td class="bg-blue l-white a" style="
                                                  width: 100%;
                                                  color: #ffffff;
                                                  font-family: 'Akzidenz Grotesk', 'Montserrat', sans-serif,
                                                    Helvetica, Arial;
                                                  font-size: 12px;
                                                  line-height: 130%;
                                                  padding-top: 16px;
                                                  padding-bottom: 4px;
                                                ">
                                %%=v(@ribbon)=%%</td></tr><tr>
                               <td class="bg-blue l-white a" width="640">
                               </td><td class="bg-blue l-white a" style="
                                                  width: 100%;
                                                  color: #ffffff;
                                                  font-family: 'Akzidenz Grotesk', 'Montserrat', sans-serif,
                                                    Helvetica, Arial;
                                                  font-size: 12px;
                                                  font-weight: 700;
                                                  padding-bottom: 14px;
                                                ">
                                <a alias="view_in_browser_header" href="%%view_email_url%%" style="color: #ffffff; text-decoration: none" target="_blank">View in browser</a></td></tr></table>
                               </td></tr></table><!--[if (gte mso 9)|(IE)]>
                               </td>
                               </tr>
                               </table>
                               <![endif]-->
                               <!-- END Logo -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
   <td class="py-15 px-30 mpy-20 mpx-15" style="font-size:0pt; line-height:0pt; text-align:center; padding-top: 15px; padding-bottom: 15px; padding-left: 30px; padding-right: 30px;"><a href="https://www.tastyrewards.com/en-us/" target="_blank" alias="Header_Logo"><img src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/1/d96cbf85-4695-4ec0-9c09-0a7a3ac41f10.png" width="154" height="140" border="0" alt="PepsiCo Tasty Rewards" style="max-width:154px; width: 100%"/></a></td>
   </tr>
   </table>
 </center>
   %%[ ENDIF ]%%
 
                    
          <!-- HEADER END -->
   
   
   
     <!--%%[IF @sub_loyalty != "PTR" THEN]%%-->
  
     
           <table cellpadding='0' cellspacing='0' border='0' role='presentation' style="max-width: 640px; width: 100%;" width="640" valign="middle">
             <tr>
               <td valign="middle" style="font-size: 0; padding: 0">
                 <a href="%%=RedirectTo(@brand_header_link)=%%" target="_blank" alias="Brand_header_link"><img src="%%=RedirectTo(@brand_header_img)=%%" style="max-width: 640px; width: 100%;" width="640" alt="%%=v(@brand_header_alt_text)=%%"></a>
               </td>
             </tr>
           </table>
      
    
       <!--%%[ENDIF]%%-->
   
   
   
 <!-- Intro -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="fz-40 lh-40 c-blue l-blue pt-60 px-20 pb-35 mpy-20" style="font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; min-width:auto !important; font-size: 40px; line-height: 40px; text-align:center; color:#0e4caa; padding-top: 60px; padding-left: 20px; padding-right: 20px; padding-bottom: 35px;"><p class="fz-40 lh-40 c-blue l-blue" style="font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; min-width:auto !important; font-size: 40px; line-height: 40px; text-align:center; color:#0e4caa;"><strong>%%=v(@memberName)=%%, <span class="m-hide"><br /></span>Enter Our Exclusive <span class="m-hide"><br /></span>Sweepstakes Now!</strong></p></td>
 </tr>
 </table>
 <!-- END Intro -->
 <!-- Your Sweepstakes -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="px-30 pb-40 mpx-15 mpb-20" style="padding-left: 30px; padding-right: 30px; padding-bottom: 40px;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="c-red l-red fz-25 lh-29 pb-28 mpb-20" style="font-family:'Akzidenz Grotesk', 'Montserrat', Arial, sans-serif; min-width:auto !important; text-align:center; color:#de202c; font-size: 25px; line-height: 29px; padding-bottom: 28px;">
 <p class="c-red l-red fz-25 lh-29" style="font-family:'Akzidenz Grotesk', 'Montserrat', Arial, sans-serif; min-width:auto !important; text-align:center; color:#de202c; font-size: 25px; line-height: 29px;">
 <strong>You</strong> could be the <strong>lucky&nbsp;winner!</strong>
 </p>
 </td>
 </tr>
 <tr>
 <td class="pb-40 mpb-20" style="padding-bottom: 40px;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <th class="column-top" width="280" style="border: 1px solid #e5e5e5; border-radius: 12px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="fluid-img" style="font-size:0pt; line-height:0pt; text-align:center;"><a href="%%=RedirectTo(@sweeps1link)=%%" target="_blank" alias="Banner_3_Image_Sweepstakes_Left"><img src="%%=RedirectTo(@sweeps1image)=%%" class="br-12-12-0-0" width="280" height="280" border="0" alt="%%=v(@sweeps1headline)=%%" style="max-width: 280px; width: 100%;"/></a></td>
 </tr>
 <tr>
 <td class="py-20 px-10 mpx-15" style="padding-top: 20px; padding-bottom: 20px; padding-left: 10px; padding-right: 10px;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="fz-20 lh-24 c-blue l-blue" style="font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; min-width:auto !important; font-size: 20px; line-height: 24px; text-align:center; color:#0e4caa;">
 <p class="fz-20 lh-24 c-blue l-blue" style="font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; min-width:auto !important; font-size: 20px; line-height: 24px; text-align:center; color:#0e4caa;"><strong>%%=v(@sweeps1headline)=%%</strong></p>
 </td>
 </tr>
 </table>
 </td>
 </tr>
 </table>
 </th>
 <th class="column mpb-20" width="16" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">&nbsp;</th>
 <th class="column-top" style="border: 1px solid #e5e5e5; border-radius: 12px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="fluid-img" style="font-size:0pt; line-height:0pt; text-align:center;"><a href="%%=RedirectTo(@sweeps2link)=%%" target="_blank" alias="Banner_3_Image_Sweepstakes_Right"><img src="%%=RedirectTo(@sweeps2image)=%%" class="br-12-12-0-0" width="280" height="280" border="0" alt="%%=v(@sweeps2headline)=%%" style="max-width: 280px; width: 100%;" /></a></td>
 </tr>
 <tr>
 <td class="py-20 px-10mpx-15" style="padding-top: 20px; padding-bottom: 20px;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="fz-20 lh-24 c-blue l-blue" style="font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; min-width:auto !important; font-size: 20px; line-height: 24px; text-align:center; color:#0e4caa;">
 <p class="fz-20 lh-24 c-blue l-blue" style="font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; min-width:auto !important; font-size: 20px; line-height: 24px; text-align:center; color:#0e4caa;"><strong>%%=v(@sweeps2headline)=%%</strong></p>
 </td>
 </tr>
 </table>
 </td>
 </tr>
 </table>
 </th>
 </tr>
 </table>
 </td>
 </tr>
 <tr>
 <td align="center">
 <!-- Button -->
 <table border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="fz-20 lh-24 l-white btnp-18-25 bg-red" style="border-radius: 50px; font-family:'GTWalsheim', 'Arimo', Arial, sans-serif; text-align:center; min-width:auto !important; font-size: 20px; line-height: 24px; color:#fffffe; mso-padding-alt:18px 25px;" bgcolor="#db1d2c"><a href="https://www.tastyrewards.com/en-us/dashboard#rewards" target="_blank" alias="Banner_3_CTA_Coupon" class="link l-white" style="display: block; padding: 18px 25px; text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;"><strong>ENTER SWEEPSTAKES</strong></span></a></td>
 </tr>
 </table>
 <!-- END Button -->
 </td>
 </tr>
 </table>
 </td>
 </tr>
 </table>
 <!-- END Your Sweepstakes -->
 <!-- Footer -->
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="py-60 px-10 mpy-20 mpx-15 bg-blue" bgcolor="#0d4ca9" style="padding-top: 60px; padding-bottom: 60px; padding-left: 10px; padding-right: 10px;">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
 <td class="pb-34 mpb-20" style="font-size:0pt; line-height:0pt; text-align:center; padding-bottom: 34px;"><a href="https://www.tastyrewards.com/en-us/" target="_blank" alias="Footer_Logo"><img src="https://image.em.pepsico.com/lib/fe4515707564067f771671/m/1/729b6c3c-da25-432b-931b-0d3f75a16ebb.png" width="132" height="120" border="0" alt="PepsiCo Tasty Rewards" style="max-width: 132px; width: 100%;" /></a></td>
 </tr>
 <tr>
     <td class="fz-10 lh-18 l-white" style="font-family:'Akzidenz Grotesk', 'Montserrat', Arial, sans-serif; min-width:auto !important; font-size: 10px; line-height: 18px; color:#fffffe; text-align:center; padding-bottom: 6px;">
         %%=v(@asterisk)=%%
     </td>
 </tr>
 <tr>
 <td class="fz-10 lh-18 l-white" style="font-family:'Akzidenz Grotesk', 'Montserrat', Arial, sans-serif; min-width:auto !important; font-size: 10px; line-height: 18px; color:#fffffe; text-align:center;">
 <p class="fz-10 lh-18 l-white" style="font-family:'Akzidenz Grotesk', 'Montserrat', Arial, sans-serif; min-width:auto !important; font-size: 10px; line-height: 18px; color:#fffffe; text-align:center;">
     %%=v(@sweeps1legal)=%%
     <br /><br />
     %%=v(@sweeps2legal)=%%
     <br /><br />
 We sent this email to: <a href="mailto:%%emailaddr%%" target="_blank" alias="email_address" style="cursor: auto; text-decoration:none; color:#fffffe;" class="link l-white"><span class="link l-white" style="text-decoration:none; color:#fffffe;">%%emailaddr%%</span></a> since you are registered as a member of our PepsiCo Tasty Rewards program. <br />
 You may unsubscribe at any time by <a href="%%unsub_center_url%%" target="_blank" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">clicking the link below</span></a>.
 <br /><br />
 <a href="%%profile_center_url%%" target="_blank" alias="Preference_center" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">Manage Preferences</span></a>
 &nbsp;&nbsp;|&nbsp;&nbsp;<a href="%%unsub_center_url%%" target="_blank" alias="Unsubscribe_footer" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">Unsubscribe</span></a>
 &nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://contact.pepsico.com/pepsico/privacy-policy" target="_blank" alias="Privacy_footer" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">Privacy Policy</span></a> 
 &nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://contact.pepsico.com/pepsico/terms-conditions" target="_blank" alias="Terms_footer" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">Terms of Use</span></a> 
 &nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://contact.pepsico.com/tastyrewardsus" target="_blank" alias="Contact_footer" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">Contact Us</span></a>
 &nbsp;&nbsp;|&nbsp;&nbsp;<a href="%%view_email_url%%" target="_blank" alias="Browser_footer" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">View in browser</span></a>
 <br /><br />
 &copy; %%xtyear%% Pep&zwj;siCo, In&zwj;c. 70&zwj;0 Ander&zwj;son Hi&zwj;ll R&zwj;d, Purc&zwj;hase, N&zwj;Y 10&zwj;577  |  <a href="tel:+18332282789" target="_blank" alias="zip_code" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">833-228-2789</span></a>
 <br /><br />
 <a href="https://www.tastyrewards.com/en-us" target="_blank" alias="tastyrewards_footer_end" class="link l-white" style="text-decoration:none; color:#fffffe;"><span class="link l-white" style="text-decoration:none; color:#fffffe;">www.tastyrewards.com/en-us</span></a>
 <br /><br />
 All trademarks and logos are property of their respective owners.
 <br />
 </p>
 </td>
 </tr>
 <tr>
     <td class="fz-10 lh-18 l-white" style="font-family:'Akzidenz Grotesk', 'Montserrat', Arial, sans-serif; min-width:auto !important; font-size: 10px; line-height: 18px; color:#fffffe; text-align:center; padding-top: 6px;">
         %%=v(@disclaimer)=%%
     </td>
 </tr>
 </table>
 </td>
 </tr>
 </table>
 <!-- END Footer -->
 <custom name="opencounter" type="tracking" />
 <custom name="usermatch" type="tracking" />
 </td>
 </tr>
 </table>
 </td>
 </tr>
 </table>
 </center>
 </body>
 </html>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
        <title><?php echo (isset($title) && $title != '') ? $title : 'Automation API' ?></title>
        <link rel="icon" type="image/x-icon" href="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>favicon.ico"/>

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
        <link href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/fontawesome/css/regular.css">
        <link rel="stylesheet" href="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/font-icons/fontawesome/css/fontawesome.css">
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>plugins.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo DEFAULT_ADMIN_CSS_PATH; ?>components/tabs.css" rel="stylesheet" type="text/css">
        
        <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/mousetrap/mousetrap.min.js"></script>
        <script src="<?php echo DEFAULT_ADMIN_ASSET_PATH; ?>plugins/src/waves/waves.min.js"></script>
        <style>
            pre{ font-size: 13px;}
        </style>
    </head>
    <body class="layout-boxed enable-secondaryNav">

        <!--  BEGIN NAVBAR  -->
        <div class="header-container container-xxl bg-white">
            <header class="header navbar navbar-expand-sm expand-header">
                <a href="<?php echo base_url() . "dashboard" ?>" class="nav-link" style="font-size: 24px;"><span class="theme-text-color decorated-first-char text-bold">A</span>UTOMATION </a>
            </header>
        </div>
        <!--  END NAVBAR  -->

        <!--  BEGIN MAIN CONTAINER  -->
        <div class="main-container" id="container">
            
            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content">
                <div class="layout-px-spacing">
                    <div class="middle-content container-xxl p-0">
                        <div class="row layout-top-spacing">
                            <div class="row layout-top-spacing">
                                <div class="col-xxl-8 col-xl-8 col-lg-10 col-md-10 col-sm-12 col-12 mx-auto">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h2>Get Started</h2>
                                            <p>This guide will help you send message using API.</p>
                                            <h5>Before You Start</h5>
                                            <span><label class="text-muted">You will need:</label> Access Token</span>
                                            <p>From where do you get Access Token?</p>
                                            <ul>
                                                <li>Login To your account : <a href="<?php echo base_url(); ?>"><?php echo base_url(); ?></a></li>
                                                <li>Click on profile picture- then select <a href="<?php echo base_url() ?>settings"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg> API settings</i></a></li>
                                                <li>You'll get your Access Token in API Credentials section.
                                                    <img src="<?php echo DEFAULT_ADMIN_IMAGE_PATH ?>docs/access-token.png" class="full-width img-fluid mt-4 mb-5">
                                                </li>    
                                            </ul>
                                            <div class="mt-3">
                                                <h3>Message Types</h3>
                                                <ul>
                                                    <li><a href="#formalized-text-message">Formalize Text Messages</a></li>
                                                    <li><a href="#template-message">Template Messages</a></li>
                                                </ul>
                                            </div>
                                            <div class="mt-4" id="formalized-text-message">
                                                <h2>Formalize Text Messages</h2>
                                                <p>Text messages are messages containing only a text body and an optional link preview.</p>
                                                
                                                <div>
                                                    <h6>Link Preview</h6>
                                                    <p class="mt-2 mb-2">You can render a preview of the first URL in the body text string, if it contains one. URLs must begin with http:// or https://. If multiple URLs are in the body text string, only the first URL will be rendered.</p>
                                                    <p class="mt-2 mb-2">If omitted, or if unable to retrieve a link preview, a clickable link will be rendered instead.</p>
                                                </div>
                                                <div class="mt-2">
                                                    <p class="text-muted mt-3 mb-3">Sample request:</p>
                                                    <div class="bg-dark m-2 p-3">
                                                        <pre>
<span class="pln">curl </span><span class="pun">-</span><span class="pln">X  POST \</span>
 <span class="text-success">'<?php echo base_url() ?>api/messages'</span><span class="pln"> \</span>
 <span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Authorization: Bearer ACCESS_TOKEN'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Content-Type: application/json'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">d </span><span class="str text-success">'{
        "to": "PHONE_NUMBER", <span class="text-muted">//With + sign and country code</span>
        "type": "text",
        "text": {
        "preview_url": true, <span class="text-muted">//<b>true</b>: if your text contain URL. <b>false</b>: if your text not contain any URL.</span>
          "body": "As requested, here's the link to our latest product: <?php echo base_url() ?>api/docs"
        }
      }'</span></pre>
                                                    </div>
                                                    <p class="text-muted mt-3 mb-3">A successful response</p>
                                                    <div class="bg-dark m-2 p-3">
                                                        <pre>
<span class="text-success">{
  "success": {
    "message": "Message accepted.",
    "code": 200
  }
}    
</span></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="mt-4" id="template-message">
                                                <h2>Template Messages</h2>
                                                <p>Before sending a template message, get verified your WhatsApp Business Account.</p>
                                                <p>Then you need to create a template. See <a href="https://www.facebook.com/business/help/2055875911147364?id=2129163877102343" target="_blank">Create Message Templates for Your WhatsApp Business Account</a> for more information</p>

                                                <div>
                                                    <p class="mt-2 mb-2">Currently, you can send the following template types:</p>
                                                    <ul>
                                                        <li><a href="#text-based">Text-based message templates</a></li>
                                                        <li><a href="#media-based">Media-based message templates</a></li>
                                                        <li><a href="#interactive">Interactive message templates</a></li>
                                                    </ul>
                                                </div>
                                                <div class="mt-5">
                                                    <h2 id="text-based">Text-Based Message Templates</h2>
                                                    <p>To send a text-based message template, make a <label class="text-success">POST</label> call to <label class="text-success"><?php echo base_url() ?>api/messages </label> and attach a message object with <label class="text-success">type=template</label>. Then, add a <label class="text-success">template object</label>.</p>
                                                    <p class="text-muted mt-3 mb-3">Sample request:</p>
                                                    <div class="bg-dark m-2 p-3">
                                                        <pre>
<span class="pln">curl </span><span class="pun">-</span><span class="pln">X  POST \</span>
 <span class="text-success">'<?php echo base_url() ?>api/messages'</span><span class="pln"> \</span>
 <span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Authorization: Bearer ACCESS_TOKEN'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Content-Type: application/json'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">d </span><span class="str text-success">'{
        "to": "PHONE_NUMBER", <span class="text-muted">//With + sign and country code</span>
        "type": "template",
        "template": {
          "name": "TEMPLATE_NAME",
          "language": {
            "code": "LANGUAGE_AND_LOCALE_CODE"
          },
          "components": [
            {
              "type": "body",
              "parameters": [
                {
                  "type": "text",
                  "text": "text-string"
                },
                {
                  "type": "currency",
                  "currency": {
                    "fallback_value": "VALUE",
                    "code": "USD",
                    "amount_1000": NUMBER
                  }
                },
                {
                  "type": "date_time",
                  "date_time": {
                    "fallback_value": "DATE"
                  }
                }
              ]
            }
          ]
        }
      }'</span></pre>
                                                    </div>
                                                    <p class="text-muted mt-3 mb-3">A successful response</p>
                                                    <div class="bg-dark m-2 p-3">
                                                        <pre>
<span class="text-success">{
  "success": {
    "message": "Message accepted.",
    "code": 200
  }
}    
</span></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-5">
                                                   <h2 id="media-based">Media-Based Message Templates</h2>
                                                   <p>To send a media-based message template, make a <label class="text-success">POST</label> call to <label class="text-success"><?php echo base_url() ?>api/messages </label> and attach a message object with <label class="text-success">type=template</label>. Then, add a <label class="text-success">template object</label>.</p>
                                                   <p>When defining your media object, you can host the asset on your server and use its URL (using the <label class="text-success">link</label> property). If using <label class="text-success">link</label>, your asset must be on a publicly accessible server or the message will fail to send.</p>
                                                    <p class="text-muted mt-3 mb-3">Sample request:</p>
                                                    <div class="bg-dark m-2 p-3">
                                                        <pre>
<span class="pln">curl </span><span class="pun">-</span><span class="pln">X  POST \</span>
 <span class="text-success">'<?php echo base_url() ?>api/messages'</span><span class="pln"> \</span>
 <span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Authorization: Bearer ACCESS_TOKEN'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Content-Type: application/json'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">d </span><span class="str text-success">'{
        "to": "PHONE_NUMBER", <span class="text-muted">//With + sign and country code</span>
        "type": "template",
        "template": {
            "name": "TEMPLATE_NAME",
            "language": {
                "code": "LANGUAGE_AND_LOCALE_CODE"
            },
            "components": [
                {
                    "type": "header",
                    "parameters": [
                    {
                        "type": "image",
                        "image": {
                            "link": "https://URL"
                        }
                    }
                ]
            },
            {
                "type": "body",
                "parameters": [
                {
                    "type": "text",
                    "text": "text-string"
                },
                {
                    "type": "currency",
                    "currency": {
                        "fallback_value": "VALUE",
                        "code": "USD",
                        "amount_1000": NUMBER
                    }
                },
                {
                    "type": "date_time",
                    "date_time": {
                        "fallback_value": "DATE"
                    }
                }
              ]
            }
          ]
        }
      }'</span></pre>
                                                    </div>
                                                    <p class="text-muted mt-3 mb-3">A successful response</p>
                                                    <div class="bg-dark m-2 p-3">
                                                        <pre>
<span class="text-success">{
  "success": {
    "message": "Message accepted.",
    "code": 200
  }
}    
</span></pre>
                                                    </div>
                                            </div>
                                            <div class="mt-5">
                                                   <h2 id="interactive">Interactive Message Templates</h2>
                                                   <p>Interactive message templates expand the content you can send recipients beyond the standard message template and media messages template types to include interactive buttons using the components object. There are two types of predefined buttons:</p>
                                                   <ul>
                                                       <li><b>Call-to-Action</b> - Allows your customer to call a phone number and visit a website.</li>
                                                       <li><b>Quick Reply</b> - Allows your customer to return a simple text message.</li>
                                                   </ul>
                                                   <p>These buttons can be attached to text messages or media messages. Once your interactive message templates have been created and approved, you can use them in notification messages as well as customer service/care messages.</p>
                                                   <p>To send a interactive  message template, make a <label class="text-success">POST</label> call to <label class="text-success"><?php echo base_url() ?>api/messages </label> and attach a message object with <label class="text-success">type=template</label>. Then, add a <label class="text-success">template object</label> with your chosen <label class="text-success">button</label>.</p>
                                                    <p class="text-muted mt-3 mb-3">Sample request:</p>
                                                    <div class="bg-dark m-3 p-3">
                                                        <pre>
<span class="pln">curl </span><span class="pun">-</span><span class="pln">X  POST \</span>
 <span class="text-success">'<?php echo base_url() ?>api/messages'</span><span class="pln"> \</span>
 <span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Authorization: Bearer ACCESS_TOKEN'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">H </span><span class="str text-success">'Content-Type: application/json'</span><span class="pln"> \
 </span><span class="pun">-</span><span class="pln">d </span><span class="str text-success">'{
        "to": "PHONE_NUMBER", <span class="text-muted">//With + sign and country code</span>
        "type": "template",
        "template": {
            "name": "TEMPLATE_NAME",
            "language": {
                "code": "LANGUAGE_AND_LOCALE_CODE"
            },
            "components": [
                {
                    "type": "header",
                    "parameters": [
                    {
                        "type": "image",
                        "image": {
                            "link": "https://URL"
                        }
                    }
                ]
            },
            {
                "type": "body",
                "parameters": [
                {
                    "type": "text",
                    "text": "text-string"
                },
                {
                    "type": "currency",
                    "currency": {
                        "fallback_value": "VALUE",
                        "code": "USD",
                        "amount_1000": NUMBER
                    }
                },
                {
                    "type": "date_time",
                    "date_time": {
                        "fallback_value": "DATE"
                    }
                }
              ]
            },
            {
                "type": "button",
                "sub_type": "quick_reply",
                "index": "0",
                "parameters": [
                  {
                    "type": "payload",
                    "payload": "PAYLOAD"
                  }
                ]
            },
            {
              "type": "button",
              "sub_type": "quick_reply",
              "index": "1",
              "parameters": [
                {
                  "type": "payload",
                  "payload": "PAYLOAD"
                }
              ]
            }
          ]
        }
      }'</span></pre>
                                                    </div>
                                                    <p class="text-muted mt-3 mb-3">A successful response</p>
                                                    <div class="bg-dark m-3 p-3">
                                                        <pre>
<span class="text-success">{
  "success": {
    "message": "Message accepted.",
    "code": 200
  }
}    
</span></pre>
                                                    </div>
                                            </div>
                                            
                                            
                                            
                                            <?php
                                            
                                            $endDateTime = date('Y-m-d').' 18:30:00';
            
                                            $current_date = new DateTime($endDateTime);
                                            $current_date->modify('-30 days');
                                            $startDateTime = $current_date->format('Y-m-d H:i:s');

                                            $start = strtotime($startDateTime);
                                            $end = strtotime($endDateTime);
                                            
                                            
                                            ?>
                                            <div class="mt-5">
                                                   <h2 id="message-details">WhatsApp Message Details & Status</h2>
                                                   <p>It provides the number and type of messages sent and delivered by the phone numbers associated with a specific WABA — for conversation.</p>
                                                   <table class="table table-bordered">
                                                       <thead>
                                                       <tr>
                                                           <th>Parameters</th>
                                                            <th>Description</th>
                                                       </tr>
                                                       </thead>
                                                       <tbody>
                                                           <tr>
                                                               <td>
                                                                   <span class="text-success">start</span><br/>
                                                                   <p>type: UNIX Timestamp</p>
                                                               </td>
                                                               <td>
                                                                   <b>Required</b><br/>
                                                                   <p>The start date for the date range you are retrieving data for.</p>
                                                               </td>
                                                           </tr>
                                                           <tr>
                                                               <td>
                                                                   <span class="text-success">end</span><br/>
                                                                   <p>type: UNIX Timestamp</p>
                                                               </td>
                                                               <td>
                                                                   <b>Required</b><br/>
                                                                   <p>The end date for the date range you are retrieving data for.</p>
                                                               </td>
                                                           </tr>
                                                           <tr>
                                                               <td>
                                                                   <span class="text-success">granularity</span><br/>
                                                                   <p>type: String</p>
                                                               </td>
                                                               <td>
                                                                   <b>Required</b><br/>
                                                                   <p>The granularity by which you would like to retrieve the data.</p>
                                                                   <ul>
                                                                       <li class="text-success">HALF_HOUR</li>
                                                                       <li class="text-success">DAY</li>
                                                                       <li class="text-success">MONTH</li>
                                                                   </ul>
                                                                   
                                                                   <p>If you select MONTH than start date and end date timestamp difference will be 2-3 months</p>
                                                               </td>
                                                           </tr>
                                                       </tbody>
                                                   </table>
                                                    <p class="text-muted mt-3 mb-3">Sample request:</p>
                                                    <div class="bg-dark m-3 p-3">
                                                        <pre>
<span class="pln">curl </span><span class="pun">-</span><span class="pln">i </span><span class="pun">-</span><span class="pln">X  GET \</span>
 <span class="text-success">'<?php echo base_url() ?>api/wa_message_details'</span>
 <span class="str text-success">'?start=<?php echo $start ?>'</span>
 <span class="str text-success">'&end=<?php echo $end ?>'</span>
 <span class="str text-success">'&granularity=DAY'</span>
 <span class="str text-success">'&access_token=ACCESS_TOKEN'</span></pre>
                                                    </div>
                                                    <p class="text-muted mt-3 mb-3">A successful response</p>
                                                    <div class="bg-dark m-3 p-3">
                                                        <pre>
<pre class="text-success"><span class="pun">{</span><span class="pln">
  </span><span class="str">"analytics"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">{</span><span class="pln">
    </span><span class="str">"phone_numbers"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[</span><span class="pln">
      </span><span class="str">"16505550111"</span><span class="pun">,</span><span class="pln">
      </span><span class="str">"16505550112"</span><span class="pun">,</span><span class="pln">
      </span><span class="str">"16505550113"</span><span class="pln">
    </span><span class="pun">],</span><span class="pln">
    </span><span class="str">"country_codes"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[</span><span class="pln">
      </span><span class="str">"US"</span><span class="pun">,</span><span class="pln">
      </span><span class="str">"BR"</span><span class="pln">
    </span><span class="pun">],</span><span class="pln">
    </span><span class="str">"granularity"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"DAY"</span><span class="pun">,</span><span class="pln">
    </span><span class="str">"data_points"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[</span><span class="pln">
      </span><span class="pun">{</span><span class="pln">
        </span><span class="str">"start"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543543200</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"end"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543629600</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"sent"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">196093</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"delivered"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">179715</span><span class="pln">
      </span><span class="pun">},</span><span class="pln">
      </span><span class="pun">{</span><span class="pln">
        </span><span class="str">"start"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543629600</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"end"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543716000</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"sent"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">147649</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"delivered"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">139032</span><span class="pln">
      </span><span class="pun">},</span><span class="pln">
      </span><span class="pun">{</span><span class="pln">
        </span><span class="str">"start"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543716000</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"end"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543802400</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"sent"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">61988</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"delivered"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">58830</span><span class="pln">
      </span><span class="pun">},</span><span class="pln">
      </span><span class="pun">{</span><span class="pln">
        </span><span class="str">"start"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543802400</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"end"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1543888800</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"sent"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">132465</span><span class="pun">,</span><span class="pln">
        </span><span class="str">"delivered"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">124392</span><span class="pln">
      </span><span class="pun">}</span><span class="pln">
      </span><span class="com"># more data points</span><span class="pln">
    </span><span class="pun">]</span><span class="pln">
  </span><span class="pun">},</span><span class="pln">
  </span><span class="str">"id"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"102290129340398"</span><span class="pln">
</span><span class="pun">}</span></pre></pre>
                                                    </div>
                                            </div>
                                            
                                            
                                            <div class="mt-5">
                                                   <h2 id="cost-details">WhatsApp Message Details With Cost</h2>
                                                   <p>It provides cost and conversation information for a specific WABA.</p>
                                                   <table class="table table-bordered">
                                                       <thead>
                                                       <tr>
                                                           <th>Parameters</th>
                                                            <th>Description</th>
                                                       </tr>
                                                       </thead>
                                                       <tbody>
                                                           <tr>
                                                               <td>
                                                                   <span class="text-success">start</span><br/>
                                                                   <p>type: UNIX Timestamp</p>
                                                               </td>
                                                               <td>
                                                                   <b>Required</b><br/>
                                                                   <p>The start date for the date range you are retrieving data for.</p>
                                                               </td>
                                                           </tr>
                                                           <tr>
                                                               <td>
                                                                   <span class="text-success">end</span><br/>
                                                                   <p>type: UNIX Timestamp</p>
                                                               </td>
                                                               <td>
                                                                   <b>Required</b><br/>
                                                                   <p>The end date for the date range you are retrieving data for.</p>
                                                               </td>
                                                           </tr>
                                                           <tr>
                                                               <td>
                                                                   <span class="text-success">granularity</span><br/>
                                                                   <p>type: String</p>
                                                               </td>
                                                               <td>
                                                                   <b>Required</b><br/>
                                                                   <p>The granularity by which you would like to retrieve the data.</p>
                                                                   <ul>
                                                                       <li class="text-success">HALF_HOUR</li>
                                                                       <li class="text-success">DAILY</li>
                                                                       <li class="text-success">MONTHLY</li>
                                                                   </ul>
                                                                   <p>If you select MONTHLY than start date and end date timestamp difference will be 2-3 months</p>
                                                               </td>
                                                           </tr>
                                                       </tbody>
                                                   </table>
                                                    <p class="text-muted mt-3 mb-3">Sample request:</p>
                                                    <div class="bg-dark m-3 p-3">
                                                        <pre>
<span class="pln">curl </span><span class="pun">-</span><span class="pln">i </span><span class="pun">-</span><span class="pln">X  GET \</span>
 <span class="text-success">'<?php echo base_url() ?>api/wa_cost_details'</span>
 <span class="str text-success">'?start=<?php echo $start; ?>'</span>
 <span class="str text-success">'&end=<?php echo $end; ?>'</span>
 <span class="str text-success">'&granularity=MONTHLY'</span>
 <span class="str text-success">'&access_token=ACCESS_TOKEN'</span></pre>
                                                    </div>
                                                    <p class="text-muted mt-3 mb-3">A successful response</p>
                                                    <div class="bg-dark m-3 p-3">
                                                         <pre class="text-success" style=""><span class="pun">{</span><span class="pln">
  </span><span class="str">"data"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[</span><span class="pln">
    </span><span class="pun">{</span><span class="pln">
      </span><span class="str">"data_points"</span><span class="pun">:</span><span class="pln"> </span><span class="pun">[</span><span class="pln">
        </span><span class="pun">{</span><span class="pln">
          </span><span class="str">"start"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1643702400</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"end"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1646121600</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"conversation"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">8500</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"conversation_type"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"REGULAR"</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"cost"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">88.1010</span><span class="pln">
        </span><span class="pun">},</span><span class="pln">
        </span><span class="pun">{</span><span class="pln">
          </span><span class="str">"start"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1643702400</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"end"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">1646121600</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"conversation”: 1000,
          "</span><span class="pln">conversation_type</span><span class="str">": "</span><span class="pln">FREE_TIER</span><span class="str">",
          "</span><span class="pln">cost</span><span class="str">": 0.0000
        }
        {
          "</span><span class="pln">start</span><span class="str">": 1643702400,
          "</span><span class="kwd">end</span><span class="str">": 1646121600,
          "</span><span class="pln">conversation</span><span class="pun">”:</span><span class="pln"> </span><span class="lit">250</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"conversation_type"</span><span class="pun">:</span><span class="pln"> </span><span class="str">"FREE_ENTRY_POINT"</span><span class="pun">,</span><span class="pln">
          </span><span class="str">"cost"</span><span class="pun">:</span><span class="pln"> </span><span class="lit">0.0000</span><span class="pln">
        </span><span class="pun">}</span><span class="pln">
      </span><span class="pun">]</span><span class="pln">
    </span><span class="pun">}</span><span class="pln">
  </span><span class="pun">]</span><span class="pln">
</span><span class="pun">}</span></pre>   
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--  BEGIN FOOTER  -->
                <div class="footer-wrapper">
                    <div class="footer-section f-section-1">
                        <p class="">Copyright © <span class="dynamic-year"><?php echo date('Y') ?></span> , All rights reserved.</p>
                    </div>
                </div>
                <!--  END FOOTER  -->
            </div>
        </div>
        <!-- END MAIN CONTAINER -->
    </body>
</html>
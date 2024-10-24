<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>


<!--  BEGIN BREADCRUMBS  -->
<div class="secondary-nav">
    <div class="breadcrumbs-container" data-page-heading="Analytics">
        <header class="header navbar navbar-expand-sm">
            <div class="d-flex breadcrumb-content">
                <div class="page-header">
                    <div class="page-title">
                        <h3>Templates</h3>
                    </div>
                </div>
            </div>
        </header>
    </div>
</div>
<!--  END BREADCRUMBS  -->
<div class="row layout-top-spacing">
    <div class="col-lg-12 mb-1">
        <?php $this->load->view('Partial/alert_view'); ?>
    </div>
    <div class="row layout-top-spacing">
        <div class="col-lg-12 layout-spacing">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <h4>Create Carousel</h4>
                        </div>
                    </div>
                </div>
                <div class=" widget-content-area">
                    <form id="create_carousel_form" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-7 col-12">
                                <div class="form-group mb-4">
                                    <label for="template_name">Template Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="template_name" name="template_name"
                                        placeholder="" required>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback">Please fill the template name</div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-12">
                                <div class="form-group mb-4">
                                    <label for="template_category">Template Category<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="template_category" name="template_category">
                                        <option disabled="disabled" selected="selected">Select Category</option>
                                        <option value="MARKETING">MARKETING</option>
                                        <option value="UTILITY">UTILITY</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-7 col-12">
                                <div class="form-group mb-4">
                                    <label for="template_language">Template Language<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="template_language" name="template_language">
                                        <option disabled="disabled" selected="selected">Select Language</option>
                                        <option value="af">Afrikaans</option>
                                        <option value="sq">Albanian</option>
                                        <option value="ar">Arabic</option>
                                        <option value="az">Azerbaijani</option>
                                        <option value="bn">Bengali</option>
                                        <option value="bg">Bulgarian</option>
                                        <option value="ca">Catalan</option>
                                        <option value="zh_CN">Chinese (CHN)</option>
                                        <option value="zh_HK">Chinese (HKG)</option>
                                        <option value="zh_TW">Chinese (TAI)</option>
                                        <option value="hr">Croatian</option>
                                        <option value="cs">Czech</option>
                                        <option value="da">Danish</option>
                                        <option value="nl">Dutch</option>
                                        <option value="en">English</option>
                                        <option value="en_GB">English (UK)</option>
                                        <option value="en_US">English (US)</option>
                                        <option value="et">Estonian</option>
                                        <option value="fil">Filipino</option>
                                        <option value="fi">Finnish</option>
                                        <option value="fr">French</option>
                                        <option value="ka">Georgian</option>
                                        <option value="de">German</option>
                                        <option value="el">Greek</option>
                                        <option value="gu">Gujarati</option>
                                        <option value="ha">Hausa</option>
                                        <option value="he">Hebrew</option>
                                        <option value="hi">Hindi</option>
                                        <option value="hu">Hungarian</option>
                                        <option value="id">Indonesian</option>
                                        <option value="ga">Irish</option>
                                        <option value="it">Italian</option>
                                        <option value="ja">Japanese</option>
                                        <option value="kn">Kannada</option>
                                        <option value="kk">Kazakh</option>
                                        <option value="rw_RW">Kinyarwanda</option>
                                        <option value="ko">Korean</option>
                                        <option value="ky_KG">Kyrgyz (Kyrgyzstan)</option>
                                        <option value="lo">Lao</option>
                                        <option value="lv">Latvian</option>
                                        <option value="lt">Lithuanian</option>
                                        <option value="mk">Macedonian</option>
                                        <option value="ms">Malay</option>
                                        <option value="ml">Malayalam</option>
                                        <option value="mr">Marathi</option>
                                        <option value="nb">Norwegian</option>
                                        <option value="fa">Persian</option>
                                        <option value="pl">Polish</option>
                                        <option value="pt_BR">Portuguese (BR)</option>
                                        <option value="pt_PT">Portuguese (POR)</option>
                                        <option value="pa">Punjabi</option>
                                        <option value="ro">Romanian</option>
                                        <option value="ru">Russian</option>
                                        <option value="sr">Serbian</option>
                                        <option value="sk">Slovak</option>
                                        <option value="sl">Slovenian</option>
                                        <option value="es">Spanish</option>
                                        <option value="es_AR">Spanish (ARG)</option>
                                        <option value="es_ES">Spanish (SPA)</option>
                                        <option value="es_MX">Spanish (MEX)</option>
                                        <option value="sw">Swahili</option>
                                        <option value="sv">Swedish</option>
                                        <option value="ta">Tamil</option>
                                        <option value="te">Telugu</option>
                                        <option value="th">Thai</option>
                                        <option value="tr">Turkish</option>
                                        <option value="uk">Ukrainian</option>
                                        <option value="ur">Urdu</option>
                                        <option value="uz">Uzbek</option>
                                        <option value="vi">Vietnamese</option>
                                        <option value="zu">Zulu</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-7 col-12">
                                <div class="form-group template_body_area">
                                    <label class="mb-0" for="bubble_message">Bubble Message<span
                                            class="text-danger">*</span></label>
                                    <div class="mb-0 text-end body_content_counter"><small
                                            class="bubble_message_count">0/1024</small></div>
                                    <textarea class="form-control template_body_text" id="bubble_message"
                                        name="bubble_message"></textarea>
                                    <div class="valid-feedback"></div>
                                    <div class="invalid-feedback bubble_message_error">Please fill the bubble message
                                    </div>
                                    <small>If you want to add variables in message use curly-brackets like "{" with
                                        numbers. eg.Hello {{1}}, Good {{2}}</small>
                                </div>
                            </div>
                            <div class="col-lg-7 col-12 dynamic_bubble_message hide">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-7 col-12">
                                <div class="carousel-cards-tab">
                                    <h6><b>Carousel cards</b></h6>
                                    <p>Display your products - create a carousel of images & buttons for up to 10 cards
                                    </p>
                                    <ul class="nav nav-tabs" id="card-tab-list" role="tablist">
                                        <li class="nav-item add_carousel_btn">
                                            <button type="button" id="add-carousel-card">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-3" id="card-tab-content">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row userMessage mt-2"></div>
                        <div class="row save_carousel_blk">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
var DEFAULT_IMAGE_UPLOAD_PATH = '<?php echo DEFAULT_IMAGE_UPLOAD_PATH; ?>'
</script>
<script src="<?php echo DEFAULT_ADMIN_JS_PATH; ?>custom_pages/templates.js"></script>

<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Create GalleryFolder
                    </header>
                    <div class="panel-body">
                        {foreach $galleryFolder as $key=>$item}
                            <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=galleryFolder&action=delete&id={$item['objectId']}" method="post">


                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="Title" id="Title" value="{$item['Title']}" class="form-control" readonly="readonly"  placeholder="Title">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Photo</label>
                                    <div class="col-lg-10">
                                        <input type="file" name="Photo" id="Photo"  class="form-control" readonly="readonly" placeholder="Photo">
                                    </div>
                                </div>


                                <input type="submit" name="delete" id="delete" class="finish btn btn-danger" value="Delete"/>
                            </form>
                        {/foreach}
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
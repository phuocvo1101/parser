
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

                        <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=galleryFolder&action=create" method="post">


                            <div class="form-group">
                                <label class="col-lg-2 control-label">Title:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="Title" id="Title" class="form-control"  placeholder="Title">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Photo</label>
                                <div class="col-lg-10">
                                    <input type="file" name="Photo" id="Photo"  class="form-control" placeholder="Photo">
                                </div>
                            </div>


                            <input type="submit" name="create" id="create" class="finish btn btn-danger" value="Create"/>
                        </form>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
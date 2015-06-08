
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Create ImageSlider
                    </header>
                    <div class="panel-body">

                        <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=imageSlider&action=create" method="post">


                            <div class="form-group">
                                <label class="col-lg-2 control-label">EventId:</label>
                                <div class="col-lg-6">
                                    <select name="EventId" id="EventId" class="form-control">
                                        {foreach $event as $key=>$item}
                                            <option selected="selected" value="{$item['objectId']}">
                                                {$item['Title']}
                                            </option>
                                        {/foreach}
                                    </select>
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
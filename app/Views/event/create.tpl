
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Create Events
                    </header>
                    <div class="panel-body">

                        <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=event&action=create" method="post">


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

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Content:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="Content" id="Content" class="form-control"  placeholder="Content">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">EventDate:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="EventDate" id="EventDate" class="form-control"  placeholder="EventDate">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">EventYear:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="EventYear" id="EventYear" class="form-control"  placeholder="EventYear">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">HEvents:</label>
                                <div class="col-lg-6">
                                    <select name="HEvents" id="HEvents" class="form-control">

                                        <option  value="1">true</option>
                                        <option   value="0">False</option>


                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Important:</label>
                                <div class="col-lg-6">
                                    <select name="Important" id="Important" class="form-control">

                                        <option  value="1">true</option>
                                        <option   value="0">False</option>


                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Memos:</label>
                                <div class="col-lg-6">
                                    <select name="Memos" id="Memos" class="form-control">

                                        <option  value="1">true</option>
                                        <option   value="0">False</option>


                                    </select>
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
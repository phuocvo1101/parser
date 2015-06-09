
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Create test
                    </header>
                    <div class="panel-body">

                        <form class="form-horizontal" id="default" action="index.php?controller=test&action=create" method="post">

                            <div class="form-group">
                                <label class="col-lg-2 control-label">mode:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="mode" id="mode" class="form-control"  placeholder="mode">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">name:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="name" id="name" class="form-control"  placeholder="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">phone:</label>
                                <div class="col-lg-10">
                                    <input type="text" name="phone" id="phone"  class="form-control" placeholder="phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">score</label>
                                <div class="col-lg-10">
                                    <input type="text" name="score" id="score"  class="form-control" placeholder="score">
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
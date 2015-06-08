
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <div class="row">
            <div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        Create Gallery
                    </header>
                    <div class="panel-body">
                        {foreach $gallery as $key=>$ga}
                            <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=gallery&action=delete&id={$ga['objectId']}" method="post">
                                <label style="color: red; text-align: center"  class="col-lg-12 control-label">{if isset($mess)}{$mess}{/if}</label>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">FolderId:</label>
                                    <div class="col-lg-6">
                                        <select name="FolderId" id="FolderId" readonly="readonly" class="form-control">

                                            {foreach $folder as $key=>$item}

                                                <option {if {$ga['FolderId']}=={$item['objectId']}}selected="selected"{/if} value="{$item['objectId']}">
                                                    {$item['Title']}
                                                </option>
                                            {/foreach}

                                        </select>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Title:</label>
                                    <div class="col-lg-6">
                                        <input type="text" name="Title" id="Title" value="{$ga['Title']}" readonly="readonly"  class="form-control" placeholder="Title">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Type:</label>
                                    <div class="col-lg-6">
                                        <select name="Type" id="Type" readonly="readonly" class="form-control">

                                            <option {if {$ga['Type']}==0}selected="selected" {/if}  value="0">Image</option>
                                            <option  {if {$ga['Type']}==1}selected="selected" {/if} value="1">Video</option>
                                            <option  {if {$ga['Type']}==2}selected="selected" {/if} value="2">Image and Video</option>

                                        </select>
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
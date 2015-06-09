<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('#Type').change(function(){
            if($(this).val()=='0') {
                $('#pho').css('display','');
                $('#vi').css('display','none');
            } else if($(this).val()=='1'){
                $('#pho').css('display','none');
                $('#vi').css('display','');
            }else{
                $('#pho').css('display','');
                $('#vi').css('display','');
            }
        });
    });

</script>
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
                        <form class="form-horizontal" id="default" enctype="multipart/form-data" action="index.php?controller=gallery&action=update&id={$ga['objectId']}" method="post">
                            <label style="color: red; text-align: center"  class="col-lg-12 control-label">{if isset($mess)}{$mess}{/if}</label>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">FolderId:</label>
                                <div class="col-lg-6">
                                    <select name="FolderId" id="FolderId" class="form-control">

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
                                    <input type="text" name="Title" id="Title" value="{$ga['Title']}"  class="form-control" placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Type:</label>
                                <div class="col-lg-6">
                                    <select name="Type" id="Type" class="form-control">

                                        <option {if {$ga['Type']}==0}selected="selected" {/if}  value="0">Image</option>
                                        <option  {if {$ga['Type']}==1}selected="selected" {/if} value="1">Video</option>
                                        <option  {if {$ga['Type']}==2}selected="selected" {/if} value="2">Image and Video</option>

                                    </select>
                                </div>
                            </div>
                            <div id="pho" class="form-group">
                                <label  class="col-lg-2 control-label">Photo</label>
                                <div class="col-lg-8">
                                    <input type="file" name="Photo" id="Photo"  class="form-control" placeholder="Photo">
                                </div>
                            </div>
                            <div style="display:none;" id="vi" class="form-group">

                                <label class="col-lg-2 control-label">Video</label>
                                <div class="col-lg-10">
                                    <input type="file" name="Video" id="Video"  class="form-control" placeholder="Video">
                                </div>
                            </div>

                            <input type="submit" name="update" id="update" class="finish btn btn-danger" value="Update"/>
                        </form>
                        {/foreach}
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
    </section>
</section>
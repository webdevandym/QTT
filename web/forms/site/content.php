<div class="content_start">
    <section id = "projectSelecter">
        <div class="customerBlock">
            <span class = 'labelStyle'>Customer</span>
            <select class = 'customer'  id = "customerID" onchange="getPjName(this.value);">
            </select>
        </div>
        <div class="pjNameBlock">
            <span class = 'labelStyle'>Project Name</span>
            <select class = 'projectName' id = "projectID">
            </select>
        </div>
    </section>
    <section id="objectBlock">
        <div class="objectProgName">
            <div class="objectType">
                <span class = 'labelStyle'>Object Type</span>
                <select id = "objectType">

                </select>
            </div>
            <div class="programerName">
                <span class = 'labelStyle'>Programmer Name</span>
                <select id = "programmerName">
                </select>
            </div>
        </div>
        <div class="objectfilter">
            <div class="objectName">
                <span class = 'labelStyle'>Object Name</span>
                <select type="text" name="objName" id = "objName">
                    
                </select>
            </div>
            <div class="objectTitle">
                <span class = 'labelStyle'>Object Title</span>
                <input type="text" name="objTitle" id = "objTitle">
            </div>
            <div class="objectButton">
                <button id = "show" class = 'btn btn-success'>Show</button>
            </div>
        </div>
    </section>

</div>

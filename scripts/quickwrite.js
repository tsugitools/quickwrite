/*Main Javascript File*/
$(document).ready(function(){
    // On load hide any alerts after 10 seconds
    setTimeout(function() {
        $(".alert-banner").slideUp();
    }, 3000);

    $('.results-collapse.collapse').on('show.bs.collapse', function(){
        var rowDiv = $(this).parent();
        rowDiv.find(".fa.rotate").addClass("open");
        rowDiv.parent().addClass("selected-row");
    }).on('hide.bs.collapse', function(){
        var rowDiv = $(this).parent();
        rowDiv.find(".fa.rotate").removeClass("open");
        rowDiv.parent().removeClass("selected-row");
    });
});
function saveTitle() {
    var sessionId = $("#sess").val();
    var titleText = $("#toolTitle").text();
    $.ajax({
        type: "post",
        url: "actions/UpdateMainTitle.php?PHPSESSID="+sessionId,
        data: {
            "toolTitle" : titleText,
            "nonav" : true
        }
    });
}
function confirmDeleteQuestion() {
    return confirm("Are you sure you want to delete this question? This action cannot be undone.");
}
function confirmDeleteQuestionBlank(questionId) {
    if ($("#questionTextInput"+questionId).val().trim().length < 1) {
        return confirm("Saving this question with blank text will delete this question. Are you sure you want to delete this question? This action cannot be undone.");
    } else {
        return true;
    }
}
function confirmResetTool() {
    return confirm("Are you sure you want to remove all questions and answers from this tool? This cannot be undone.");
}
function showNewQuestionRow() {
    var questionLink = $("#addQuestionLink");
    var questionRow = $("#newQuestionRow");

    questionLink.hide();
    questionRow.fadeIn();
    var theForm = $("#questionTextForm-1");
    theForm.find('#questionTextInput-1').focus()
        .off("keypress").on("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
                theForm.submit();
            }
        });
    $(".questionSaveAction-1").off("click").on("click", function(e) {
        theForm.submit();
    });
    $(".questionCancelAction-1").off("click").on("click", function(e) {
        $("#questionTextInput-1").val('');
        questionRow.hide();
        questionLink.show();
    });
}
function editQuestionText(questionId) {
    $("#questionText"+questionId).hide();
    $(".questionDeleteAction"+questionId).hide();
    $(".questionEditAction"+questionId).hide();
    $(".questionSaveAction"+questionId).show()
        .off("click").on("click", function(e) {
            theForm.submit();
        });
    $(".questionCancelAction"+questionId).show()
        .off("click").on("click", function(e) {
            var theText = $("#questionText"+questionId);
            theText.show();
            theForm.hide();
            $("#questionTextInput"+questionId).val(theText.text());
            $(".questionDeleteAction"+questionId).show();
            $(".questionEditAction"+questionId).show();
            $(".questionSaveAction"+questionId).hide();
            $(".questionCancelAction"+questionId).hide();
        });
    var theForm = $("#questionTextForm"+questionId);
    theForm.show();
    theForm.find('#questionTextInput'+questionId).focus()
        .off("keypress").on("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
                theForm.submit();
            }
        });
}
function editTitleText() {
    $("#toolTitle").hide();
    var titleForm = $("#toolTitleForm");
    titleForm.show();
    titleForm.find("#toolTitleInput").focus()
        .off("keypress").on("keypress", function(e) {
        if(e.which === 13) {
            e.preventDefault();
            titleForm.submit();
        }
    });
    $("#toolTitleSaveLink").show()
        .off("click").on("click", function(e) {
            titleForm.submit();
        });
    $("#toolTitleCancelLink").show()
        .off("click").on("click", function(e) {
            var titleText = $("#toolTitle");
            titleText.show();
            titleForm.hide();
            $("#toolTitleInput").val($(".title-text-span").text());
            $("#toolTitleCancelLink").hide();
            $("#toolTitleSaveLink").hide();
        });
}

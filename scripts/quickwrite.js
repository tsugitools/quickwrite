/*Main Javascript File*/
$(document).ready(function(){

    setupAlertHide();

    $('.results-collapse.collapse').on('show.bs.collapse', function(){
        var rowDiv = $(this).parent();
        rowDiv.find(".fa.rotate").addClass("open");
        rowDiv.parent().addClass("selected-row");
    }).on('hide.bs.collapse', function(){
        var rowDiv = $(this).parent();
        rowDiv.find(".fa.rotate").removeClass("open");
        rowDiv.parent().removeClass("selected-row");
    });

    $("#importModal").on("hidden.bs.modal", function() {
        $(this).find('.results-collapse.collapse').collapse("hide");
        $(this).find("input[name='question']").prop("checked", false);
    });
});
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
function showNewQuestionRow() {
    var addQuestionsSection = $("#addQuestions");
    var questionRow = $("#newQuestionRow");

    addQuestionsSection.hide();
    questionRow.fadeIn();
    var theForm = $("#questionTextForm-1");
    theForm.find('#questionTextInput-1').focus()
        .off("keypress").on("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: theForm.prop("action"),
                    data: theForm.serialize(),
                    success: function(data) {
                        $("#questionTextInput-1").val('');
                        $("#newQuestionNumber").text(data.next_question + '.');
                        $("#newQuestionRow").before(data.new_question);
                        $("#flashmessages").html(data.flashmessage);
                        setupAlertHide();
                        questionRow.hide();
                        addQuestionsSection.show();
                    }
                });
            }
        });
    $("#questionSaveAction-1").off("click").on("click", function(e) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: theForm.prop("action"),
            data: theForm.serialize(),
            success: function(data) {
                $("#questionTextInput-1").val('');
                $("#newQuestionNumber").text(data.next_question + '.');
                $("#newQuestionRow").before(data.new_question);
                $("#flashmessages").html(data.flashmessage);
                setupAlertHide();
                questionRow.hide();
                addQuestionsSection.show();
            }
        });
    });
    $("#questionCancelAction-1").off("click").on("click", function(e) {
        $("#questionTextInput-1").val('');
        questionRow.hide();
        addQuestionsSection.show();
    });
}
function editQuestionText(questionId) {
    var questionText =$("#questionText"+questionId);
    questionText.hide();
    $("#questionDeleteAction"+questionId).hide();
    $("#questionEditAction"+questionId).hide();
    $("#questionReorderAction"+questionId).hide();

    var theForm = $("#questionTextForm"+questionId);

    theForm.show();
    theForm.find('#questionTextInput'+questionId).focus()
        .off("keypress").on("keypress", function(e) {
            if(e.which === 13) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: theForm.prop("action"),
                    data: theForm.serialize(),
                    success: function(data) {
                        questionText.text($('#questionTextInput'+questionId).val());
                        questionText.show();
                        $("#questionDeleteAction"+questionId).show();
                        $("#questionEditAction"+questionId).show();
                        $("#questionReorderAction"+questionId).show();
                        $("#questionSaveAction"+questionId).hide();
                        $("#questionCancelAction"+questionId).hide();
                        theForm.hide();
                        $("#flashmessages").html(data.flashmessage);
                        setupAlertHide();
                    }
                });
            }
        });
    $("#questionSaveAction"+questionId).show()
        .off("click").on("click", function(e) {
            $.ajax({
                type: "POST",
                url: theForm.prop("action"),
                data: theForm.serialize(),
                success: function(data) {
                    questionText.text($('#questionTextInput'+questionId).val());
                    questionText.show();
                    $("#questionDeleteAction"+questionId).show();
                    $("#questionEditAction"+questionId).show();
                    $("#questionReorderAction"+questionId).show();
                    $("#questionSaveAction"+questionId).hide();
                    $("#questionCancelAction"+questionId).hide();
                    theForm.hide();
                    $("#flashmessages").html(data.flashmessage);
                    setupAlertHide();
                }
            });
    });

    $("#questionCancelAction"+questionId).show()
        .off("click").on("click", function(e) {
        var theText = $("#questionText"+questionId);
        theText.show();
        theForm.hide();
        $("#questionTextInput"+questionId).val(theText.text());
        $("#questionDeleteAction"+questionId).show();
        $("#questionEditAction"+questionId).show();
        $("#questionReorderAction"+questionId).show();
        $("#questionSaveAction"+questionId).hide();
        $("#questionCancelAction"+questionId).hide();
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
            $.ajax({
                type: "POST",
                dataType: "json",
                url: titleForm.prop("action"),
                data: titleForm.serialize(),
                success: function(data) {
                    $(".title-text-span").text($("#toolTitleInput").val());
                    var titleText = $("#toolTitle");
                    titleText.show();
                    titleForm.hide();
                    $("#toolTitleCancelLink").hide();
                    $("#toolTitleSaveLink").hide();
                    $("#flashmessages").html(data.flashmessage);
                    setupAlertHide();
                }
            });
        }
    });
    $("#toolTitleSaveLink").show()
        .off("click").on("click", function(e) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: titleForm.prop("action"),
                data: titleForm.serialize(),
                success: function(data) {
                    $(".title-text-span").text($("#toolTitleInput").val());
                    var titleText = $("#toolTitle");
                    titleText.show();
                    titleForm.hide();
                    $("#toolTitleCancelLink").hide();
                    $("#toolTitleSaveLink").hide();
                    $("#flashmessages").html(data.flashmessage);
                    setupAlertHide();
                }
            });
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
function setupAlertHide() {
    // On load hide any alerts after 10 seconds
    setTimeout(function() {
        $(".alert-banner").slideUp();
    }, 3000);
}

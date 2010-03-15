/*
 * class to create an instance of the overview section of the main document. It will
 * initially serve as a test to ensure that the implemented stuff works
 */
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.data.ModelData;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.TextField;


import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextArea;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.i18n.client.DateTimeFormat;
import org.wits.client.Document;
import org.wits.client.EditDocumentDialog;

/**
 *
 * @author luigi
 */
public class OverView {

    private Dialog overViewDialog = new Dialog();
    // private Dialog topicListingDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private Button saveButton = new Button("Next");
    private Button browseTopicsButton = new Button("Browse Topics");
    private TextArea topicField = new TextArea();
    private String qA1 = "", qA3 = "", qA2 = "", qA4 = "", qA5 = "";
    //private ComboBox<Group> groupField = new ComboBox<Group>();
    TextField<String> questionA1 = new TextField<String>();

    public OverView() {
        createUI();
    }

    //creates the GUI in which the selected text will be displayed. sets only one
    //layer for the interface.
    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(600);
        mainForm.setWidth(850);

        questionA1.setFieldLabel("A.1. Name of course/ unit.");
        questionA1.setEmptyText("Enter the course/unit name");
        questionA1.setAllowBlank(false);
        questionA1.setMinLength(100);

        mainForm.add(questionA1, formData);

        overViewDialog.setButtonAlign(HorizontalAlignment.LEFT);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        // set radio buttons, their labels and thrir positon relative
        // to the mainForm...
        Radio radio = new Radio();
        radio.setBoxLabel("proposal for a new course/unit ");
        radio.setValue(true);

        Radio radio2 = new Radio();
        radio2.setBoxLabel("swfvqewrfgqgfqegqrgqgr ");
        radio2.setBoxLabel("change to the outcomes or credit value of a course/unit");

        Radio radio3 = new Radio();
        //radio3.setPosition(5, 10);
        radio3.setBoxLabel("linked to other recent course/unit proposal/s, or proposal/s currently in development ");
        radio3.setValue(true);

        Radio radio4 = new Radio();
        radio4.setPagePosition(96, 360);
        radio4.setBoxLabel("linked to other recent course/unit amendment/s, or amendment/s currently in development");

        Radio radio5 = new Radio();
        radio5.setPagePosition(96, 375);
        radio5.setBoxLabel("linked to a new qualification/ programme proposal, or one currently in development");

        Radio radio6 = new Radio();
        radio6.setPagePosition(96, 389);
        radio6.setBoxLabel("linked to a recent qualification/ programme amendment, or one currently in development");

        Radio radio7 = new Radio();
        radio7.setPagePosition(96, 403);
        radio7.setBoxLabel("not linked to any other recent academic developments, nor those currently in development ");

        RadioGroup questionA2 = new RadioGroup();
        questionA2.setFieldLabel("A.2. This is a");
        questionA2.add(radio);
        questionA2.add(radio2);
        mainForm.add(questionA2, formData);

        TextArea questionA3 = new TextArea();
        questionA3.setPreventScrollbars(false);
        questionA3.setHeight(200);
        questionA3.setFieldLabel("Description");
        mainForm.add(questionA3, formData);

        RadioGroup questionA5 = new RadioGroup();
        questionA5.setFieldLabel("A.5. This new or amended course proposal is");
        questionA5.add(radio3);
        questionA5.add(radio4);
        questionA5.add(radio5);
        questionA5.add(radio6);
        questionA5.add(radio7);

        mainForm.add(questionA5, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        //function to ensure that all the fields are filled and the form is
        //completed before the user moves to the next form
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                qA1 = questionA1.getValue();// deptField.getValue().getId();
                if (qA1 == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question A.1.", null);
                    return;
                }


                /*qA2=questionA2.getCheckBoxSelector();
                if (qA2 == null){
                MessageBox.info("Missing selection", "Please make a selection for question A.2.", null);
                return;
                }
                //mainForm.add(qA2,formData);*/


                /*qA3 = questionA3.getValue();// deptField.getValue().getId();
                if (qA3 == null) {
                MessageBox.info("Missing answer", "Provide your answer to question A.1.", null);
                return;
                }

                qA3 = questions.getValue();// deptField.getValue().getId();
                if (qA3 == null) {
                MessageBox.info("Missing department", "Provide your answer to question 1", null);
                return;
                }

                qA4 = questions.getValue();// deptField.getValue().getId();
                if (qA4 == null) {
                MessageBox.info("Missing department", "Provide your answer to question 1", null);
                return;
                }

                qA5 = questions.getValue();// deptField.getValue().getId();
                if (qA5 == null) {
                MessageBox.info("Missing department", "Provide your answer to question 1", null);
                return;
                }*/

                RulesAndSyllabus rulesAndSyllabus = new RulesAndSyllabus();
                rulesAndSyllabus.show();
                overViewDialog.hide();
            }
        });

        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.RIGHT);

        overViewDialog.setBodyBorder(false);
        overViewDialog.setHeading("Section A: Overview");
        overViewDialog.setWidth(900);
        overViewDialog.setHeight(700);
        overViewDialog.setHideOnButtonClick(true);
        overViewDialog.setButtons(Dialog.CLOSE);
        overViewDialog.setButtonAlign(HorizontalAlignment.LEFT);
        overViewDialog.setHideOnButtonClick(true);

        overViewDialog.add(mainForm);

        //setDepartment();
    }

    public void show() {
        overViewDialog.show();
    }

    public void setSelectedFolder(ModelData selectedFolder) {
        this.selectedFolder = selectedFolder;
        topicField.setValue((String) this.selectedFolder.get("id"));
        topicField.setToolTip((String) this.selectedFolder.get("id"));
    }

    private void createNewDocument(String url) {
        final MessageBox wait = MessageBox.wait("Wait",
                "Saving your data, please wait...", "Saving...");
        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        String responseTxt[] = response.getText().split(",");

                        Document doc = new Document();
                        //doc.setDate(fmt.format(date));
                        doc.setRefNo(responseTxt[0]);
                        doc.setId(responseTxt[1]);
                        doc.setQuestion(qA1);
                        //doc.setQuestion(qA2);
                        //doc.setQuestion(qA3);
                        /*doc.setQuestion(qA4);
                        doc.setQuestion(qA5);*/
                        EditDocumentDialog editDocumentDialog = new EditDocumentDialog(doc, "all", null);
                        editDocumentDialog.show();
                        overViewDialog.setVisible(false);
                        wait.close();
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }
    }
}

/*
 * class to create an instance of the overview section of the main document. It will
 * initially serve as a test to ensure that the implemented stuff works
 */



/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package org.wits.client;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.data.ModelData;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.store.ListStore;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.Style;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.ComboBox;
import com.extjs.gxt.ui.client.widget.form.ComboBox.TriggerAction;
import com.extjs.gxt.ui.client.widget.form.DateField;
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.form.CheckBox;
import com.extjs.gxt.ui.client.widget.CheckBoxListView;
import com.extjs.gxt.ui.client.data.BeanModel;
import com.extjs.gxt.ui.client.data.BeanModelReader;
import com.extjs.gxt.ui.client.util.Format;
import com.extjs.gxt.ui.client.event.SelectionChangedEvent;
import com.extjs.gxt.ui.client.event.Listener;
import com.extjs.gxt.ui.client.event.Events;
import com.extjs.gxt.ui.client.data.BeanModel;


import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextArea;
import com.extjs.gxt.ui.client.widget.layout.BorderLayout;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import com.google.gwt.i18n.client.DateTimeFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

/**
 *
 * @author davidwaf
 */
public class rulesAndSyllabus {

    private Dialog overViewDialog = new Dialog();
   // private Dialog topicListingDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private Button saveButton = new Button("Save");
    private Button browseTopicsButton = new Button("Browse Topics");
    private TextArea topicField = new TextArea();
    private String qB1 = "",qB2="",qB3a="",qB3b="", qB4a="",qB4b="", qB4c, qB5a="", qB5b="",qB6a="", qB6b="", qB6c="";
    //private ComboBox<Group> groupField = new ComboBox<Group>();
    TextField<String> questionA1 = new TextField<String>();
    TextField<String> questionB1 = new TextField<String>();
    TextField<String> questionB2 = new TextField<String>();
    TextField<String> questionB3a = new TextField<String>();
    TextField<String> questionB3b = new TextField<String>();
    TextField<String> questionB4b = new TextField<String>();
    TextField<String> questionB4c = new TextField<String>();
    TextField<String> questionB5b = new TextField<String>();


    public rulesAndSyllabus() {
        createUI();
    }

    //creates the GUI in which the selected text will be displayed. sets only one
    //layer for the interface.

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(650);
        mainForm.setWidth(750);
        mainForm.setLabelWidth(400);

        questionB1.setFieldLabel("B.1. How does this course/unit change the rules for the curriculum? ");
        questionB1.setEmptyText("Enter the course/unit name");
        questionB1.setAllowBlank(false);
        questionB1.setMinLength(100);

        mainForm.add(questionB1, formData);

        questionB2.setFieldLabel("B.2. Describe the course/unit syllabus. ");
        questionB2.setEmptyText("Enter the course/unit name");
        questionB2.setAllowBlank(false);
        questionB2.setMinLength(100);

        mainForm.add(questionB2, formData);

        questionB3a.setFieldLabel("B.3.a. What are the pre-requisites for the course/unit if any? ");
        questionB3a.setEmptyText("Enter the course/unit name");
        questionB3a.setAllowBlank(false);
        questionB3a.setMinLength(100);

        mainForm.add(questionB3a, formData);

        questionB3b.setFieldLabel("B.3.b. What are the co-requisites for the course/unit if any? ");
        questionB3b.setEmptyText("Enter the course/unit name");
        questionB3b.setAllowBlank(false);
        questionB3b.setMinLength(100);

        mainForm.add(questionB3b, formData);

        Radio radio = new Radio();
        radio.setBoxLabel("a compulsory course/unit ");
        radio.setValue(true);

        Radio radio2 = new Radio();
        //radio2.setBoxLabel("swfvqewrfgqgfqegqrgqgr ");
        radio2.setBoxLabel("an optional course/unit");

        Radio radio3 = new Radio();
        //radio3.setPosition(5, 10);
        radio3.setBoxLabel("both compulsory and optional as the course/unit is offered toward qualifications/ programmes with differing curriculum structures ");
        //radio3.setValue(true);


        Radio radio4 = new Radio();
        //radio4.setPagePosition(96,360);
        radio4.setBoxLabel("a 1st year unit");
        radio4.setValue(true);

        Radio radio5 = new Radio();
        //radio5.setPagePosition(96,375);
        radio5.setBoxLabel("a 2nd year unit");

        Radio radio6 = new Radio();
        //radio6.setPagePosition(96,389);
        radio6.setBoxLabel("a 3rd year unit");

        Radio radio7 = new Radio();
        //radio7.setPagePosition(96,403);
        radio7.setBoxLabel("a 4th year unit ");

        Radio radio8 = new Radio();
        //radio8.setPagePosition(96,403);
        radio8.setBoxLabel("a 5th year unit ");

        Radio radio9 = new Radio();
        //radio9.setPagePosition(96,403);
        radio9.setBoxLabel("a 6th year unit ");

        Radio radio10 = new Radio();
        //radio10.setPagePosition(96,403);
        radio10.setBoxLabel("an honours unit ");

        Radio radio11 = new Radio();
        //radio11.setPagePosition(96,403);
        radio11.setBoxLabel("a postgraduate diploma unit ");

        Radio radio12 = new Radio();
        //radio12.setPagePosition(96,403);
        radio12.setBoxLabel("a masters unit ");

        Radio radio13 = new Radio();
        //radio12.setPagePosition(96,403);
        radio13.setBoxLabel("full year unit offered in semester 1 and 2 ");
        radio13.setValue(true);

        Radio radio14 = new Radio();
        //radio12.setPagePosition(96,403);
        radio14.setBoxLabel("half year unit offered in  ");

        Radio radio15 = new Radio();
        //radio12.setPagePosition(96,403);
        radio15.setBoxLabel("semester1 ");

        Radio radio16 = new Radio();
        //radio12.setPagePosition(96,403);
        radio16.setBoxLabel("semester 2 ");

        Radio radio17 = new Radio();
        //radio12.setPagePosition(96,403);
        radio17.setBoxLabel("or semester 1 and 2  ");

        Radio radio18 = new Radio();
        //radio12.setPagePosition(96,403);
        radio18.setBoxLabel("block unit offered in ");

        Radio radio19 = new Radio();
        //radio12.setPagePosition(96,403);
        radio19.setBoxLabel("block 1 ");

        Radio radio20 = new Radio();
        //radio12.setPagePosition(96,403);
        radio20.setBoxLabel("block 2 ");

        Radio radio21 = new Radio();
        //radio12.setPagePosition(96,403);
        radio21.setBoxLabel("block 3 ");

        Radio radio22 = new Radio();
        //radio12.setPagePosition(96,403);
        radio22.setBoxLabel("block 4");

        Radio radio23 = new Radio();
        //radio12.setPagePosition(96,403);
        radio23.setBoxLabel("attendance course/unit");

        Radio radio24 = new Radio();
        radio24.setPagePosition(96,500);
        radio24.setBoxLabel("other ");

        Radio radio25 = new Radio();
        //radio12.setPagePosition(96,403);
        radio25.setBoxLabel("yes ");
        radio25.setValue(true);

        Radio radio26 = new Radio();
        //radio12.setPagePosition(96,403);
        radio26.setBoxLabel("no ");



        /*Radio radio13 = new Radio();
        radio13.setPagePosition(96,403);
        radio13.setBoxLabel("not linked to any other recent academic developments, nor those currently in development ");*/

        RadioGroup questionA2 = new RadioGroup();
        questionA2.setFieldLabel("A.2. This is a");
        questionA2.add(radio);
        questionA2.add(radio2);
        questionA2.add(radio3);
        mainForm.add(questionA2, formData);

        questionB4b.setFieldLabel("B.4.b. If it is a compulsory course/unit, which course/unit is it replacing, or is the course/unit to be taken by students in addition to the current workload of courses/unit? ");
        questionB4b.setEmptyText("Enter the course/unit name");
        questionB4b.setAllowBlank(false);
        //questionB4b.setMinLength(100);
        questionB4b.setSize(40, 50);
        //questionB4b.

        mainForm.add(questionB4b, formData);

        questionB4c.setFieldLabel("B.4.c. If it is both a compulsory and optional course/unit, provide details explaining for which qualifications/ programmes the course/unit would be optional and for which it would be compulsory. ");
        questionB4c.setEmptyText("Enter the course/unit name");
        questionB4c.setAllowBlank(false);
        questionB4c.setMinLength(100);

        mainForm.add(questionB4c, formData);

        RadioGroup questionB5a = new RadioGroup();
        questionB5a.setFieldLabel("B.5.a. At what level is the course/unit taught?");
        questionB5a.add(radio4);
        questionB5a.add(radio5);
        questionB5a.add(radio6);
        questionB5a.add(radio7);
        questionB5a.add(radio8);
        questionB5a.add(radio9);
        questionB5a.add(radio10);
        questionB5a.add(radio11);
        questionB5a.add(radio12);

        mainForm.add(questionB5a, formData);
        
        questionB5b.setFieldLabel("B.5.b. In which year/s of study is the course/unit to be taught?  ");
        questionB5b.setEmptyText("Enter the course/unit name");
        questionB5b.setAllowBlank(false);
        questionB5b.setMinLength(100);

        mainForm.add(questionB5b, formData);


        RadioGroup questionB6a = new RadioGroup();
        questionB6a.setFieldLabel("B.6.a. This is a ");
        questionB6a.add(radio13);
        questionB6a.add(radio14);
        questionB6a.add(radio15);
        questionB6a.add(radio16);
        questionB6a.add(radio17);
        questionB6a.add(radio18);
        questionB6a.add(radio19);
        questionB6a.add(radio20);
        questionB6a.add(radio21);
        questionB6a.add(radio22);
        questionB6a.add(radio23);
        questionB6a.add(radio24);

        mainForm.add(questionB6a, formData);

        TextArea questionB6b = new TextArea();
        questionB6b.setPreventScrollbars(false);
        questionB6b.setHeight(200);
        questionB6b.setFieldLabel("B.6.b. If ‘other’, provide details of the course/unit duration and/or the number of lectures which comprise the course/unit ");
        mainForm.add(questionB6b, formData);

        RadioGroup questionB6c = new RadioGroup();
        questionB6c.setFieldLabel("B.6.c.Is the unit assessed ");
        questionB6c.add(radio25);
        questionB6c.add(radio26);

        mainForm.add(questionB6c, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        //dont forget to add constraints for radioGroups. need to find out how.
         saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                qB1 = questionB1.getValue();// deptField.getValue().getId();
                if (qB1 == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question A.1.", null);
                    return;
                }


                qB2=questionB2.getValue();
                if (qB2 == null){
                    MessageBox.info("Missing selection", "Please make a selection for question A.2.", null);
                    return;
                }

                qB3a = questionB3a.getValue();// deptField.getValue().getId();
                if (qB3a == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question A.1.", null);
                    return;
                }

                qB3b = questionB3b.getValue();// deptField.getValue().getId();
                if (qB3b == null) {
                    MessageBox.info("Missing department", "Provide your answer to question 1", null);
                    return;
                }

                qB4b = questionB4b.getValue();// deptField.getValue().getId();
                if (qB4b == null) {
                    MessageBox.info("Missing department", "Provide your answer to question 1", null);
                    return;
                }

                qB4c = questionB4c.getValue();// deptField.getValue().getId();
                if (qB4c == null) {
                    MessageBox.info("Missing department", "Provide your answer to question 1", null);
                    return;
                }

                qB5b = questionB5b.getValue();// deptField.getValue().getId();
                if (qB5b == null) {
                    MessageBox.info("Missing department", "Provide your answer to question 1", null);
                    return;
                }

            }
         });

        //mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.RIGHT);
        //FormButtonBinding binding = new FormButtonBinding(mainForm);
        //binding.addButton(saveButton);

        overViewDialog.setBodyBorder(false);
        overViewDialog.setHeading("New Document");
        overViewDialog.setWidth(800);
        overViewDialog.setHeight(700);
        overViewDialog.setHideOnButtonClick(true);
        overViewDialog.setButtons(Dialog.CLOSE);
        overViewDialog.setButtonAlign(HorizontalAlignment.LEFT);
        overViewDialog.setHideOnButtonClick(true);
        overViewDialog.setButtons(Dialog.YES);
        overViewDialog.setButtonAlign(HorizontalAlignment.RIGHT);
        overViewDialog.setHideOnButtonClick(true);
        //newDocumentDialog.setButtons(Dialog.);
        //newDocumentDialog.setButtonAlign(HorizontalAlignment.RIGHT);

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
                        doc.setQuestion(qB1);
                        doc.setQuestion(qB2);
                        doc.setQuestion(qB5b);
                        /*doc.setQuestion(qA4);
                        doc.setQuestion(qA5);*/
                        EditDocumentDialog editDocumentDialog = new EditDocumentDialog(doc,"all",null);
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

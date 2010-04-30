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

import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextArea;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.Response;
import com.google.gwt.i18n.client.DateTimeFormat;
import org.wits.client.Constants;
import org.wits.client.util.WicidXML;
//import org.wits.client.ads.OverView;

/**
 *
 * @author davidwaf
 */
public class RulesAndSyllabusOne {

    private Dialog rulesAndSyllabusOneDialog = new Dialog();
    // private Dialog topicListingDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormPanel qA2Panel = new FormPanel();
    private FormPanel qB5aPanel = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private TextArea topicField = new TextArea();
    private String courseTitle;
    private OverView overView;
    private RulesAndSyllabusOne oldRulesAndSyllabusOne;
    private RulesAndSyllabusTwo oldRulesAndSyllabusTwo;
    private String qB1 = "", qB2 = "", qB3a = "", qB3b = "", qB4a = "", qB4b = "", qB4c = "";
    public String rulesAndSyllabusOneData;
    //private NewCourseProposalDialog newCourseProposalDialog;
    //private String overViewData = overView.getOverViewData();

    public RulesAndSyllabusOne(OverView overViewDialog) {
        this.overView = overViewDialog;
        createUI();
    }

    public RulesAndSyllabusOne(RulesAndSyllabusTwo oldRulesAndSyllabusTwo) {
        this.oldRulesAndSyllabusTwo = oldRulesAndSyllabusTwo;
        createUI();
    }

    //creates the GUI in which the selected text will be displayed. sets only one
    //layer for the interface.
    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(530);
        mainForm.setWidth(650);
        mainForm.setLabelWidth(300);

        Radio radio = new Radio();
        radio.setBoxLabel("a compulsory course/unit ");
        radio.setValue(true);

        Radio radio2 = new Radio();
        radio2.setPagePosition(331, 345);
        radio2.setBoxLabel("an optional course/unit");

        Radio radio3 = new Radio();
        radio3.setPagePosition(331, 361);
        radio3.setBoxLabel("both compulsory and optional as the course/unit is offered toward qualifications/programmes with differing curriculum structures ");

        final TextArea questionB1 = new TextArea();
        questionB1.setPreventScrollbars(false);
        questionB1.setHeight(50);
        questionB1.setFieldLabel("B.1. How does this course/unit change the rules for the curriculum? ");

        mainForm.add(questionB1, formData);

        final TextArea questionB2 = new TextArea();
        questionB2.setPreventScrollbars(false);
        questionB2.setHeight(50);
        questionB2.setFieldLabel("B.2. Describe the course/unit syllabus. ");

        mainForm.add(questionB2, formData);

        final TextArea questionB3a = new TextArea();
        questionB3a.setPreventScrollbars(false);
        questionB3a.setHeight(50);
        questionB3a.setFieldLabel("B.3. a. What are the pre-requisites for the course/unit if any? ");

        mainForm.add(questionB3a, formData);

        final TextArea questionB3b = new TextArea();
        questionB3b.setPreventScrollbars(false);
        questionB3b.setHeight(50);
        questionB3b.setFieldLabel("B.3.b. What are the co-requisites for the course/unit if any? ");

        mainForm.add(questionB3b, formData);


        final RadioGroup questionB4a = new RadioGroup();
        questionB4a.setFieldLabel("B.4.a This is a");
        questionB4a.add(radio);
        questionB4a.add(radio2);
        questionB4a.add(radio3);
        //mainForm.add(questionA2, formData);

        qA2Panel.setFrame(false);
        qA2Panel.setBodyBorder(false);
        //q5Panel.setPosition(200, 600);
        qA2Panel.setHeight(110);
        qA2Panel.setWidth(700);
        qA2Panel.setLabelWidth(300);
        mainForm.add(qA2Panel, formData);
        qA2Panel.add(questionB4a, formData);

        final TextArea questionB4b = new TextArea();
        questionB4b.setPreventScrollbars(false);
        questionB4b.setHeight(50);
        questionB4b.setFieldLabel("B.4.b. If it is a compulsory course/unit, which course/unit is it replacing, or is the course/unit to be taken by students in addition to the current workload of courses/unit? ");

        mainForm.add(questionB4b, formData);

        final TextArea questionB4c = new TextArea();
        questionB4c.setPreventScrollbars(false);
        questionB4c.setHeight(50);
        questionB4c.setFieldLabel("B.4.c. If it is both a compulsory and optional course/unit, provide details explaining for which qualifications/ programmes the course/unit would be optional and for which it would be compulsory. ");

        mainForm.add(questionB4c, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        //dont forget to add constraints for radioGroups. need to find out how.
        //used to ensure that all the data is added into the required fields
        //before saving the content. Will not proceed unless all fields are entered
        
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                //replaceAll is used to replace spaces which give problems when trying to save to the database. spaces(" ")
                //are replaced by ("--")
                qB1 = questionB1.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB1 == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question B.1.", null);
                    return;
                }else{
                    qB1.toString().replaceAll(" ", "--");

                }

                qB2 = questionB2.getValue();//.toString().replaceAll(" ", "--");
                if (qB2 == null) {
                    MessageBox.info("Missing selection", "Please make a selection for question B.2.", null);
                    return;
                }else{
                    qB2.toString().replaceAll(" ", "--");

                }

                qB3a = questionB3a.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB3a == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question B.3.a.", null);
                    return;
                }else{
                    qB3a.toString().replaceAll(" ", "--");

                }

                qB3b = questionB3b.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB3b == null) {
                    MessageBox.info("Missing department", "Provide your answer to question B.3.b", null);
                    return;
                }else{
                    qB3b.toString().replaceAll(" ", "--");

                }

                qB4a = questionB4a.getValue().getBoxLabel().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB4a == null) {
                    MessageBox.info("Missing department", "Provide your answer to question B.4.a", null);
                    return;
                }

                qB4b = questionB4b.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB4b == null) {
                    MessageBox.info("Missing department", "Provide your answer to question B.4.b", null);
                    return;
                }else{
                    qB4b.toString().replaceAll(" ", "--");

                }

                qB4c = questionB4c.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB4c == null) {
                    MessageBox.info("Missing department", "Provide your answer to question B.4.c", null);
                    return;
                }else{
                    qB4c.toString().replaceAll(" ", "--");
                }
                storeDocumentInfo();

                //data saved into a single string with each data varieable seperated by ("_")

                //rulesAndSyllabusOneData = qB1+"_"+qB2+"_"+qB3a+"_"+qB3b+"_"+qB4a+"_"+qB4b+"_"+qB4c;//wicidXML.getXml();

                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveformdata&formname="+"rulesandsyllabusone"+"&formdata=" + rulesAndSyllabusOneData+"&docid=" + Constants.docid;

                        createDocument(url);

                if (oldRulesAndSyllabusTwo == null) {
                    RulesAndSyllabusTwo rulesAndSyllabusTwo = new RulesAndSyllabusTwo(RulesAndSyllabusOne.this);
                    rulesAndSyllabusTwo.show();
                    rulesAndSyllabusOneDialog.hide();
                } else {
                    oldRulesAndSyllabusTwo.show();
                    rulesAndSyllabusOneDialog.hide();
                }
            }
        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                overView.setOldRulesAndSyllabusOne(RulesAndSyllabusOne.this);
                overView.show();
                rulesAndSyllabusOneDialog.hide();
                storeDocumentInfo();
            }
        });

        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.RIGHT);

        //mainForm.setButtonAlign(HorizontalAlignment.RIGHT);

        //FormButtonBinding binding = new FormButtonBinding(mainForm);
        //binding.addButton(saveButton);

        rulesAndSyllabusOneDialog.setBodyBorder(false);
        rulesAndSyllabusOneDialog.setHeading("Section B: Rules and Syllabus Book- Page One");
        rulesAndSyllabusOneDialog.setWidth(700);
        rulesAndSyllabusOneDialog.setHeight(600);
        rulesAndSyllabusOneDialog.setHideOnButtonClick(true);
        rulesAndSyllabusOneDialog.setButtons(Dialog.CLOSE);
        rulesAndSyllabusOneDialog.setButtonAlign(HorizontalAlignment.LEFT);
        rulesAndSyllabusOneDialog.setHideOnButtonClick(true);
        rulesAndSyllabusOneDialog.setButtons(Dialog.CLOSE);

        rulesAndSyllabusOneDialog.setButtonAlign(HorizontalAlignment.RIGHT);
        rulesAndSyllabusOneDialog.setHideOnButtonClick(true);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        //newDocumentDialog.setButtons(Dialog.);
        //newDocumentDialog.setButtonAlign(HorizontalAlignment.RIGHT);
        rulesAndSyllabusOneDialog.setButtonAlign(HorizontalAlignment.LEFT);

        rulesAndSyllabusOneDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();
            }
        });

        rulesAndSyllabusOneDialog.add(mainForm);

        //setDepartment();
    }

    public void storeDocumentInfo(){
        WicidXML wicidxml = new WicidXML("rulesAndSyllabusOne");
        wicidxml.addElement("qB1", qB1);
        wicidxml.addElement("qb2", qB2);
        wicidxml.addElement("qb3a", qB3a);
        wicidxml.addElement("qb3b", qB3b);
        wicidxml.addElement("qb4a", qB4a);
        wicidxml.addElement("qb4b", qB4b);
        wicidxml.addElement("qb4c", qB4c);
        rulesAndSyllabusOneData = wicidxml.getXml();

    }

    public void setDocumentInfo(){

    }

    public void show() {
        rulesAndSyllabusOneDialog.show();
    }

    public void setOldRulesAndSyllabusOne(RulesAndSyllabusTwo oldRulesAndSyllabusTwo) {
        this.oldRulesAndSyllabusTwo = oldRulesAndSyllabusTwo;
    }

    public void setSelectedFolder(ModelData selectedFolder) {
        this.selectedFolder = selectedFolder;
        topicField.setValue((String) this.selectedFolder.get("id"));
        topicField.setToolTip((String) this.selectedFolder.get("id"));
    }

    private void createDocument(String url) {

        RequestBuilder builder = new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    String resp[] = response.getText().split("|");

                    if (resp[0].equals("")) {
                        /*if (oldOverView == null) {

                        Constants.docid = resp[1];
                        OverView overView = new OverView(NewCourseProposalDialog.this);
                        overView.show();
                        newDocumentDialog.hide();
                        } else {
                        oldOverView.show();
                        newDocumentDialog.hide();

                        }*/
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (Exception e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }

    }
}

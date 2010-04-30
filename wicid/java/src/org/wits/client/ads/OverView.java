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
import com.google.gwt.http.client.Response;
import com.google.gwt.i18n.client.DateTimeFormat;

import com.extjs.gxt.ui.client.data.*;
import com.google.gwt.core.client.GWT;
import org.wits.client.Constants;
import org.wits.client.util.WicidXML;

//import com.extjs.gxt.ui.client.data.DataReader;
/**
 *
 * @author luigi
 */
public class OverView {

    private Dialog overViewDialog = new Dialog();
    // private Dialog topicListingDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormPanel q5Panel = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private Button forwardButton = new Button("Forward to...");
    private TextArea topicField = new TextArea();
    private String qA1 = "", qA3 = "", qA2 = "", qA4 = "", qA5 = "";
    private TextField<String> questionA1 = new TextField<String>();
    private NewCourseProposalDialog newCourseProposalDialog;
    private RulesAndSyllabusOne oldRulesAndSyllabusOne;
    public String overViewData;
    private ForwardTo forwardTo;


    public OverView(NewCourseProposalDialog newCourseProposalDialog) {
        this.newCourseProposalDialog = newCourseProposalDialog;
        createUI();
    }

    public OverView(RulesAndSyllabusOne oldRulesAndSyllabusOne) {
        this.oldRulesAndSyllabusOne = oldRulesAndSyllabusOne;
        createUI();
    }

    //creates the GUI in which the selected text will be displayed. sets only one
    //layer for the interface.NewCourseProposalDialog newCourseProposalDialog
    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(570);
        mainForm.setWidth(780);
        mainForm.setLabelWidth(200);

        //test database entry using the first input field on overview...
        
        
        overViewDialog.setButtonAlign(HorizontalAlignment.LEFT);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        // set radio buttons, their labels and thrir positon relative
        // to the mainForm...
        Radio radio = new Radio();
        radio.setBoxLabel("proposal for a new course/unit ");
        radio.setValue(true);
        radio.getValueAttribute();

        Radio radio2 = new Radio();
        radio2.setBoxLabel("change to the outcomes or credit value of a course/unit");

        Radio radio3 = new Radio();
        //radio3.setPosition(5, 10);
        radio3.setBoxLabel("linked to other recent course/unit proposal/s, or proposal/s currently in development ");
        radio3.setValue(true);

        Radio radio4 = new Radio();
        radio4.setPagePosition(231, 430);
        radio4.setBoxLabel("linked to other recent course/unit amendment/s, or amendment/s currently in development");

        Radio radio5 = new Radio();
        radio5.setPagePosition(231, 450);
        radio5.setBoxLabel("linked to a new qualification/ programme proposal, or one currently in development");

        Radio radio6 = new Radio();
        radio6.setPagePosition(231, 470);
        radio6.setBoxLabel("linked to a recent qualification/ programme amendment, or one currently in development");

        Radio radio7 = new Radio();
        radio7.setPagePosition(231, 490);
        radio7.setBoxLabel("not linked to any other recent academic developments, nor those currently in development ");

        questionA1.setFieldLabel("A.1. Name of course/ unit.");
        questionA1.setEmptyText("Enter the course/unit name");
        questionA1.setValue(newCourseProposalDialog.getTitleField().getValue());
        questionA1.setAllowBlank(false);
        questionA1.setMinLength(100);
        questionA1.getValue();

        mainForm.add(questionA1, formData);


        final RadioGroup questionA2 = new RadioGroup();
        questionA2.setFieldLabel("A.2. This is a");
        questionA2.add(radio);
        questionA2.add(radio2);
        mainForm.add(questionA2, formData);

        final TextArea questionA3 = new TextArea();
        questionA3.setPreventScrollbars(false);
        questionA3.setHeight(120);
        questionA3.setFieldLabel("A.3. Provide a brief motivation for the introduction/ amendment of the course/unit ");
        mainForm.add(questionA3, formData);

        final TextArea questionA4 = new TextArea();
        questionA4.setPreventScrollbars(false);
        questionA4.setHeight(120);
        questionA4.setFieldLabel("A.4. Towards which qualification(s) can the course/unit be taken? ");
        mainForm.add(questionA4, formData);

        final RadioGroup questionA5 = new RadioGroup();
        questionA5.setSelectionRequired(true);
        questionA5.setFieldLabel("A.5. This new or amended course proposal is");
        questionA5.add(radio3);
        questionA5.add(radio4);
        questionA5.add(radio5);
        questionA5.add(radio6);
        questionA5.add(radio7);


        q5Panel.setFrame(false);
        q5Panel.setBodyBorder(false);
        //q5Panel.setPosition(200, 600);
        q5Panel.setHeight(200);
        q5Panel.setWidth(700);
        q5Panel.setLabelWidth(200);
        mainForm.add(q5Panel, formData);
        q5Panel.add(questionA5, formData);


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
                qA1 = questionA1.getValue();//.replaceAll(" ", "--");// deptField.getValue().getId();
                if (qA1 == null) {
                    MessageBox.info("Missing answer", "Provide an answer to question A.1.", null);
                    return;
                }else{
                    qA1.replaceAll(" ", "--");
                }

                qA2=questionA2.getValue().getBoxLabel().replaceAll(" ", "--");
                if (qA2 == null){
                    MessageBox.info("Missing selection", "Please make a selection for question A.2.", null);
                    return;
                }else{
                    qA2.replaceAll(" ", "--");

                }
                
                qA3 = questionA3.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qA3 == null) {
                    MessageBox.info("Missing answer", "Provide an answer to question A.3.", null);
                    return;
                }else{
                    qA3.toString().replaceAll(" ", "--");
                }
                //MessageBox.info("test", "missing", null);

                qA4 = questionA4.getValue();//.toString();//.replaceAll(" ", "");// deptField.getValue().getId();
                if (qA4 == null) {
                    MessageBox.info("Missing department", "Provide your answer to question A.4.", null);
                    return;
                }else{
                    qA4.toString().replaceAll(" ","--");
                }

                qA5 = questionA5.getValue().getBoxLabel().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qA5 == null) {
                    MessageBox.info("Missing department", "Please make a selection for question A.5.", null);
                    return;
                }else{
                    qA5.replaceAll(" ", "--");
                }
                
                storeDocumentInfo();
                
                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname="+"overview"+"&formdata=" +overViewData+"&docid="+Constants.docid;
                
                createDocument(url);
                
                if (oldRulesAndSyllabusOne == null){
                    RulesAndSyllabusOne rulesAndSyllabusOne = new RulesAndSyllabusOne(OverView.this);
                    rulesAndSyllabusOne.show();
                    overViewDialog.hide();
                }
                else{

                    oldRulesAndSyllabusOne.show();
                    overViewDialog.hide();

                }
                
            }
        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                newCourseProposalDialog.setOldOverView(OverView.this);
                newCourseProposalDialog.show();
                overViewDialog.hide();
                storeDocumentInfo();
            }
        });

         forwardButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                ForwardTo forwardToDialog = new ForwardTo();
                forwardToDialog.show();
                storeDocumentInfo();
            }
        });

        
        mainForm.addButton(backButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);


        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        mainForm.addButton(forwardButton);

        overViewDialog.setBodyBorder(false);
        overViewDialog.setHeading("Section A: Overview");
        overViewDialog.setWidth(800);
        overViewDialog.setHeight(650);
        overViewDialog.setHideOnButtonClick(true);
        overViewDialog.setButtons(Dialog.CLOSE);
        overViewDialog.setButtonAlign(HorizontalAlignment.LEFT);
        overViewDialog.setHideOnButtonClick(true);

        overViewDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();
            }
        });

        overViewDialog.add(mainForm);

        //setDepartment();
    }

    public void storeDocumentInfo(){
        WicidXML wicidxml = new WicidXML("overview");
        wicidxml.addElement("qA1", qA1);
        wicidxml.addElement("qA2", qA2);
        wicidxml.addElement("qA3", qA3);
        wicidxml.addElement("qA4", qA4);
        wicidxml.addElement("qA5", qA5);
        overViewData = wicidxml.getXml();
    }

    public void setDocumentInfo(){

    }

    public void setOldRulesAndSyllabusOne(RulesAndSyllabusOne oldRulesAndSyllabusOne) {
        this.oldRulesAndSyllabusOne = oldRulesAndSyllabusOne;
    }

    public void show() {
        overViewDialog.show();
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

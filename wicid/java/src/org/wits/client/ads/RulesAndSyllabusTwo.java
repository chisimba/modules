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
import com.extjs.gxt.ui.client.event.BaseEvent;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.Events;
import com.extjs.gxt.ui.client.event.Listener;
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


/**
 *
 * @author davidwaf
 */
public class RulesAndSyllabusTwo {

    private Dialog rulesAndSyllabusTwoDialog = new Dialog();
    // private Dialog topicListingDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormPanel qA2Panel = new FormPanel();
    private FormPanel qB5aPanel = new FormPanel();
    private FormPanel qB6aPanel = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private TextArea topicField = new TextArea();
    private Radio radio24 = new Radio();
    private Radio radio14 = new Radio();
    private Radio radio15 = new Radio();
    private Radio radio16 = new Radio();
    private Radio radio17 = new Radio();
    private Radio radio18 = new Radio();
    private Radio radio19 = new Radio();
    private Radio radio20 = new Radio();
    private Radio radio21 = new Radio();
    private Radio radio22 = new Radio();
    private String courseTitle;
    private OverView overView;
    private RulesAndSyllabusOne rulesAndSyllabusOne;
    private SubsidyRequirements oldSubsidyRequirements;
    private String rulesAndSyllabusTwoData, qB5a, qB5b, qB6a, qB6b, qB6c;
    //private SavedFormData savedFormData;
    //private String rulesAndSyllabusOneData = rulesAndSyllabusOne.getRulesAndSyllabusDataOne().toString();

    public RulesAndSyllabusTwo(RulesAndSyllabusOne rulesAndSyllabusOne) {
        this.rulesAndSyllabusOne = rulesAndSyllabusOne;
        createUI();
    }

    public RulesAndSyllabusTwo(SubsidyRequirements oldSubsidyRequirements) {
        this.oldSubsidyRequirements = oldSubsidyRequirements;
        createUI();
    }

    //creates the GUI in which the selected text will be displayed. sets only one
    //layer for the interface.
    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(650);
        mainForm.setWidth(810);
        mainForm.setLabelWidth(300);

        Radio radio4 = new Radio();
        radio4.setBoxLabel("a 1st year unit");
        radio4.setValue(true);
        radio4.getBoxLabel();

        Radio radio5 = new Radio();
        radio5.setPagePosition(331,126);
        radio5.setBoxLabel("a 2nd year unit");

        Radio radio6 = new Radio();
        radio6.setPagePosition(331,142);
        radio6.setBoxLabel("a 3rd year unit");

        Radio radio7 = new Radio();
        radio7.setPagePosition(331,162);
        radio7.setBoxLabel("a 4th year unit ");

        Radio radio8 = new Radio();
        radio8.setPagePosition(331,182);
        radio8.setBoxLabel("a 5th year unit ");

        Radio radio9 = new Radio();
        radio9.setPagePosition(331,202);
        radio9.setBoxLabel("a 6th year unit ");

        Radio radio10 = new Radio();
        radio10.setPagePosition(331,222);
        radio10.setBoxLabel("an honours unit ");

        Radio radio11 = new Radio();
        radio11.setPagePosition(331,242);
        radio11.setBoxLabel("a postgraduate diploma unit ");

        Radio radio12 = new Radio();
        radio12.setPagePosition(331,262);
        radio12.setBoxLabel("a masters unit ");

        Radio radio13 = new Radio();
        //radio12.setPagePosition(96,403);
        radio13.setBoxLabel("full year unit offered in semester 1 and 2 ");
        radio13.setValue(true);

        radio14.setPagePosition(331,410);
        radio14.setBoxLabel("half year unit offered in  ");

        radio15.setPagePosition(477,410);
        radio15.setBoxLabel("semester1 ");

        radio16.setPagePosition(477,426);
        radio16.setBoxLabel("semester 2 ");

        radio17.setPagePosition(477,442);
        radio17.setBoxLabel("or semester 1 and 2  ");

        radio18.setPagePosition(331,458);
        radio18.setBoxLabel("block unit offered in ");

        radio19.setPagePosition(460,458);
        radio19.setBoxLabel("block 1 ");

        radio20.setPagePosition(460,474);
        radio20.setBoxLabel("block 2 ");

        radio21.setPagePosition(460,490);
        radio21.setBoxLabel("block 3 ");

        radio22.setPagePosition(460,506);
        radio22.setBoxLabel("block 4");

        Radio radio23 = new Radio();
        radio23.setPagePosition(331,522);
        radio23.setBoxLabel("attendance course/unit");

        
        radio24.setPagePosition(331,538);
        radio24.setBoxLabel("other ");

        Radio radio25 = new Radio();
        //radio12.setPagePosition(96,403);
        radio25.setBoxLabel("yes ");
        radio25.setValue(true);

        Radio radio26 = new Radio();
        //radio12.setPagePosition(96,403);
        radio26.setBoxLabel("no ");

        final RadioGroup questionB5a = new RadioGroup();
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


        qB5aPanel.setFrame(false);
        qB5aPanel.setBodyBorder(false);
        //q5Panel.setPosition(200, 600);
        qB5aPanel.isHeaderVisible();
        qB5aPanel.setHeight(230);
        qB5aPanel.setWidth(700);
        qB5aPanel.setLabelWidth(300);
        mainForm.add(qB5aPanel, formData);
        qB5aPanel.add(questionB5a, formData);

        final TextArea questionB5b = new TextArea();
        questionB5b.setPreventScrollbars(false);
        questionB5b.setHeight(50);
        questionB5b.setFieldLabel("B.5.b. In which year/s of study is the course/unit to be taught? ");
        //questionB5b.getValue();

        mainForm.add(questionB5b, formData);

        // for B6a use checkboxes instead of radio buttons
        final RadioGroup questionB6a1 = new RadioGroup();
        questionB6a1.setFieldLabel("B.6.a. This is a ");
        questionB6a1.setSelectionRequired(true);
        questionB6a1.add(radio13);
        questionB6a1.add(radio14);
        questionB6a1.add(radio18);
        questionB6a1.add(radio23);
        questionB6a1.add(radio24);

        final RadioGroup questionB6a2 = new RadioGroup();
        questionB6a2.setSelectionRequired(true);
        questionB6a1.add(radio15);
        questionB6a1.add(radio16);
        questionB6a1.add(radio17);

        final RadioGroup questionB6a3 = new RadioGroup();
        questionB6a3.setSelectionRequired(true);
        questionB6a1.add(radio19);
        questionB6a1.add(radio20);
        questionB6a1.add(radio21);
        questionB6a1.add(radio22);

        qB6aPanel.setFrame(false);
        qB6aPanel.setBodyBorder(false);
        //q5Panel.setPosition(200, 600);
        qB6aPanel.isHeaderVisible();
        qB6aPanel.setHeight(200);
        qB6aPanel.setWidth(830);
        qB6aPanel.setLabelWidth(300);
                
        qB6aPanel.add(questionB6a1, formData);
        qB6aPanel.add(questionB6a2, formData);
        qB6aPanel.add(questionB6a3, formData);

        mainForm.add(qB6aPanel, formData);

        if (radio14.getValue() == false) {
            radio15.disable();
            radio16.disable();
            radio17.disable();
        }
        if (radio18.getValue() == false) {
            radio19.disable();
            radio20.disable();
            radio21.disable();
            radio22.disable();
        }

        final TextArea questionB6b = new TextArea();
        questionB6b.setPreventScrollbars(false);
        questionB6b.setHeight(50);
        questionB6b.setWidth(50);
        questionB6b.setFieldLabel("B.6.b. If ‘other’, provide details of the course/unit duration and/or the number of lectures which comprise the course/unit ");
        mainForm.add(questionB6b, formData);

        if (radio24.getValue() == false) {
            questionB6b.disable();
        }

        questionB6a1.addListener(Events.Change, new Listener<BaseEvent>(){
            public void handleEvent(BaseEvent be) {
                if (radio24.getValue() == false){
                    questionB6b.disable();
                }
                if (radio24.getValue() == true){
                    questionB6b.enable();
                }
                if (radio14.getValue() == false){
                    radio15.disable();
                    radio16.disable();
                    radio17.disable();
                }
                if (radio14.getValue() == true){
                    radio15.enable();
                    radio16.enable();
                    radio17.enable();
                }
                if (radio18.getValue() == false){
                    radio19.disable();
                    radio20.disable();
                    radio21.disable();
                    radio22.disable();
                }
                if (radio18.getValue() == true){
                    radio19.enable();
                    radio20.enable();
                    radio21.enable();
                    radio22.enable();
                }
            }
        });
       
        final RadioGroup questionB6c = new RadioGroup();
        questionB6c.setFieldLabel("B.6.c.Is the unit assessed ");
        questionB6c.add(radio25);
        questionB6c.add(radio26);

        mainForm.add(questionB6c, formData);

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
                
                qB5a = questionB5a.getValue().getBoxLabel().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB5a == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question B.5.a", null);
                    return;
                }


                qB5b = questionB5b.getValue();//.toString().replaceAll(" ", "--");
                if (qB5b == null) {
                    MessageBox.info("Missing answer", "Please make a selection for question B.5.b", null);
                    return;
                }else{
                    qB5b.toString().replaceAll(" ", "--");

                }

                qB6a = questionB6a1.getValue().getBoxLabel().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB6a == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question B.6.a", null);
                    return;
                }

                qB6b = questionB6b.getValue();//.toString().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB6b == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question B.6.b", null);
                    return;
                }else{
                    qB6b.toString().replaceAll(" ", "--");
                }

                qB6c = questionB6c.getValue().getBoxLabel().replaceAll(" ", "--");// deptField.getValue().getId();
                if (qB6c == null) {
                    MessageBox.info("Missing answer", "Provide your answer to question B.6.c", null);
                    return;
                }

                //WicidXml util function might be used at a later stage...

                storeDocumentInfo();
                //data saved into a single string with each data varieable seperated by ("_")
                
                //rulesAndSyllabusTwoData = qB5a+"_"+qB5b+"_"+qB6a+"_"+qB6b+"_"+qB6c;
               
                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname="+"rulesandsyllabustwo"+"&formdata="+rulesAndSyllabusTwoData+"&docid="+Constants.docid;

                createDocument(url);

                if(oldSubsidyRequirements == null){
                    SubsidyRequirements subsidyRequirements = new SubsidyRequirements(RulesAndSyllabusTwo.this);
                    subsidyRequirements.show();
                    rulesAndSyllabusTwoDialog.hide();
                }
                else{
                    oldSubsidyRequirements.show();
                    rulesAndSyllabusTwoDialog.hide();
                }
            }
        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

                @Override
                public void componentSelected(ButtonEvent ce) {
                    rulesAndSyllabusOne.setOldRulesAndSyllabusOne(RulesAndSyllabusTwo.this);
                    rulesAndSyllabusOne.show();
                    rulesAndSyllabusTwoDialog.hide();
                    storeDocumentInfo();
                }
            });

        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.RIGHT);

        rulesAndSyllabusTwoDialog.setBodyBorder(false);
        rulesAndSyllabusTwoDialog.setHeading("Section B: Rules and Syllabus Book- Page Two");
        rulesAndSyllabusTwoDialog.setWidth(830);
        rulesAndSyllabusTwoDialog.setHeight(720);
        rulesAndSyllabusTwoDialog.setHideOnButtonClick(true);
        rulesAndSyllabusTwoDialog.setButtons(Dialog.CLOSE);
        rulesAndSyllabusTwoDialog.setButtonAlign(HorizontalAlignment.LEFT);
        rulesAndSyllabusTwoDialog.setHideOnButtonClick(true);
        rulesAndSyllabusTwoDialog.setButtons(Dialog.CLOSE);
        rulesAndSyllabusTwoDialog.setButtonAlign(HorizontalAlignment.RIGHT);
        rulesAndSyllabusTwoDialog.setHideOnButtonClick(true);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        rulesAndSyllabusTwoDialog.setButtonAlign(HorizontalAlignment.LEFT);

        rulesAndSyllabusTwoDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();
            }
        });

        rulesAndSyllabusTwoDialog.add(mainForm);

        //setDepartment();
    }

    public void storeDocumentInfo() {
        WicidXML wicidxml = new WicidXML("rulesAndSyllabusTwo");
        wicidxml.addElement("qb5a", qB5a);
        wicidxml.addElement("qb5b", qB5b);
        wicidxml.addElement("qb6a", qB6a);
        wicidxml.addElement("qb6b", qB6b);
        wicidxml.addElement("qb6c", qB6c);
        rulesAndSyllabusTwoData = wicidxml.getXml();
    }

    public void setDocumentInfo(){

    }

    public void show() {
        rulesAndSyllabusTwoDialog.show();
    }

    public void setOldRulesAndSyllabusTwo(SubsidyRequirements oldSubsidyRequirements) {
        this.oldSubsidyRequirements = oldSubsidyRequirements;
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

package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.event.BaseEvent;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.Events;
import com.extjs.gxt.ui.client.event.Listener;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.MultiField;
import com.extjs.gxt.ui.client.widget.form.NumberField;
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
import org.wits.client.Constants;
import org.wits.client.util.WicidXML;

/**
 *
 * @author Jacqueline
 *
 */
public class SubsidyRequirements {

    private Dialog subsidyRequirementsDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private Button forwardButton = new Button("Forward to...");
    private TextArea questionC1 = new TextArea();
    private NumberField questionC3 = new NumberField();
    private MultiField questionC4b = new MultiField();
    private Radio radioC4a1 = new Radio();
    private Radio radioC4a2 = new Radio();
    private Radio radioC2a1 = new Radio();
    private TextArea questionC2b = new TextArea();
    private TextArea q4b1 = new TextArea();
    private TextArea q4b2 = new TextArea();
    private RulesAndSyllabusTwo rulesAndSyllabusTwo;
    private OutcomesAndAssessmentOne outcomesAndAssessmentOne;
    private OutcomesAndAssessmentOne oldOutcomesAndAssessmentOne;
    //private SubsidyRequirements oldSubsidyRequirements;
    private String subsidyRequirementsData;
    private String qC1, qC2a, qC2b, qC3, qC4a, qC4b1, qC4b2;
    private RadioGroup questionC2a = new RadioGroup();
    private RadioGroup questionC4a = new RadioGroup();

    public SubsidyRequirements(RulesAndSyllabusTwo rulesAndSyllabusTwo) {
        this.rulesAndSyllabusTwo = rulesAndSyllabusTwo;
        createUI();
    }

    public SubsidyRequirements(OutcomesAndAssessmentOne oldOutcomesAndAssessmentOne) {
        this.oldOutcomesAndAssessmentOne = oldOutcomesAndAssessmentOne;
        createUI();

    }

    //creates the GUI in which the selected text will be displayed. sets only one
    //layer for the interface.
    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(500);
        mainForm.setWidth(740);
        mainForm.setLabelWidth(250);

        questionC1.setFieldLabel("C.1. The mode of instruction is understood to "
                + "be contact/face-to-face lecturing. Provide details if any other "
                + "mode of delivery is to be used");
        questionC1.setEmptyText("");
        questionC1.setAllowBlank(false);

        mainForm.add(questionC1, formData);

        radioC2a1.setBoxLabel("off-campus");
        Radio radioC2a2 = new Radio();
        radioC2a2.setBoxLabel("on-campus");
        radioC2a1.setValue(true);
        questionC2b.disable();
        radioC2a1.enableEvents(true);


        questionC2a.setFieldLabel("C.2.a. The course/unit is taught");
        questionC2a.add(radioC2a1);
        questionC2a.add(radioC2a2);
        mainForm.add(questionC2a, formData);

        questionC2a.addListener(Events.Change, new Listener<BaseEvent>(){
            public void handleEvent(BaseEvent be) {
                if (radioC2a1.getValue() == false){
                    questionC2b.disable();
                }
                if (radioC2a1.getValue() == true){
                    questionC2b.enable();
                }
            }

        });



        questionC2b.setPreventScrollbars(false);
        questionC2b.setFieldLabel("C.2.b. If the course/unit is taught off-campus provide details");
        mainForm.add(questionC2b, formData);

        questionC3.setFieldLabel("C.3. What is the third order CESM (Classification of "
                + "Education SUbject Matter) category for the course/unit? (The CESM manual "
                + "can be downloaded from http://intranet.wits.ac.za/Academic/APO/CESMs.htm)");
        questionC3.setEmptyText("Enter CESM category");
        questionC3.setAllowDecimals(false);
        questionC3.setAllowNegative(false);
        mainForm.add(questionC3, formData);


        radioC4a1.setBoxLabel("Yes");
        radioC4a2.setBoxLabel("No");
        radioC4a1.setValue(true);
        
        
        questionC4a.setFieldLabel("C.4.a. Is any other School/Entity involved in teaching this unit?");
        questionC4a.add(radioC4a1);
        questionC4a.add(radioC4a2);

        questionC4a.addListener(Events.Change, new Listener<BaseEvent>(){
            public void handleEvent(BaseEvent e) {
                if (radioC4a1.getValue() == true){
                    questionC4b.disable();
                }
                if (radioC4a1.getValue() == false){
                    questionC4b.enable();
                }
            }

        });

        mainForm.add(questionC4a, formData);

        questionC4b.setFieldLabel("C.4.b. If yes, state the name of the School/Entity percentage each teaches.");

        q4b1.setPreventScrollbars(false);
        q4b1.setEmptyText("Enter School/Entity name");
        q4b1.setWidth(365);
        q4b2.setPreventScrollbars(false);
        q4b2.setEmptyText("Percentage");
        q4b2.setWidth(80);

        questionC4b.add(q4b1);
        questionC4b.add(q4b2);
        mainForm.add(questionC4b, formData);



        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));



        //function to ensure that all the fields are filled and the form is
        //completed before the user moves to the next form
        //
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                
                if ((radioC2a1.getValue() == true) && (questionC2b.getValue() == null)) {
                    MessageBox.info("Missing answer", "Provide an answer to C.2.b", null);
                    return;
                }
                
                if (questionC3.getValue() == null) {
                    MessageBox.info("Missing answer", "Provide an answer to C.3.", null);
                    return;
                }
                
                if ((radioC4a1.getValue() == true) && (questionC4b.getValue() == null)) {
                    MessageBox.info("Missing answer", "Provide an answer to C.4.b.", null);
                    return;
                }
                

                if (questionC3.getRawValue().length() != 6) {
                    MessageBox.info("Error", "The CESM category must be a 6 digit number", null);
                    return;
                }


                if (radioC4a1.getValue() == true) {

                    String[] q4b1No;
                    String[] q4b2No;

                    try {
                        q4b1No = (q4b1.getValue()).split("\r\n|\r|\n");
                    }
                    catch (NullPointerException npe) {
                        MessageBox.info("Missing answer", "Provide an answer to C.4.b (School/Entity)", null);
                        return;
                    }

                    try {
                        q4b2No = q4b2.getValue().split("\r\n|\r|\n");
                    }
                    catch (NullPointerException e) {
                        MessageBox.info("Missing answer", "Provide an answer to C.4.b (percentage)", null);
                        return;
                    }

                    if (q4b1No.length > q4b2No.length) {
                        MessageBox.info("Error", "Missing a percentage of a School or Entity", null);
                        return;
                    }

                    else if (q4b2No.length > q4b1No.length) {
                        MessageBox.info("Error", "Missing a name of a School or Entity", null);
                        return;
                    }


                    int x = 0;
                    int total = 0;

                    while (x < (q4b2No.length)) {
                        try {
                            int ques4b2 = 0;
                            try {
                                ques4b2 = Integer.parseInt(q4b2No[x]);

                            } catch (NumberFormatException nfe) {
                                MessageBox.info("Error", "Please use a percentage.", null);
                                return;
                            }
                            total = total + ques4b2;

                            if (ques4b2 > 100) {
                                MessageBox.info("Error", "The maximum percentage is 100", null);
                                return;
                            }
                            if (ques4b2 < 1) {
                                MessageBox.info("Error", "The minimum percentage is 1", null);
                                return;
                            }
                            x++;

                        } catch (Exception e) {
                            MessageBox.info("Error: " + e.toString(), "The percentage "
                                    + "must be a number between 1 and 100", null);
                            return;
                        }
                    }

               /*     if (total != 100) {
                        MessageBox.info("Error", "The percentages must add up to 100", null);
                        return;
                    }*/
                }
                

                storeDocumentInfo();
                

                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname=" + "sudsidyrequirements" + "&formdata=" + subsidyRequirementsData + "&docid=" + Constants.docid;

                createDocument(url);
                if (oldOutcomesAndAssessmentOne == null) {

                    OutcomesAndAssessmentOne outcomesAndAssessmentOne = new OutcomesAndAssessmentOne(SubsidyRequirements.this);
                    outcomesAndAssessmentOne.show();
                    subsidyRequirementsDialog.hide();
                } else {

                    oldOutcomesAndAssessmentOne.show();
                    subsidyRequirementsDialog.hide();
                }

            }
        });


        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                rulesAndSyllabusTwo.setOldRulesAndSyllabusTwo(SubsidyRequirements.this);
                rulesAndSyllabusTwo.show();
                subsidyRequirementsDialog.hide();
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
        mainForm.addButton(forwardButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);


        subsidyRequirementsDialog.setBodyBorder(false);
        subsidyRequirementsDialog.setHeading("Section C: Subsidy Requirements");
        subsidyRequirementsDialog.setWidth(750);
        subsidyRequirementsDialog.setHeight(570);
        subsidyRequirementsDialog.setHideOnButtonClick(true);
        subsidyRequirementsDialog.setButtons(Dialog.CLOSE);
        subsidyRequirementsDialog.setButtonAlign(HorizontalAlignment.LEFT);

        subsidyRequirementsDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();
            }
        });

        subsidyRequirementsDialog.add(mainForm);


    }

    public void storeDocumentInfo() {

        qC1 = questionC1.getValue();
        qC2a = questionC2a.getValue().getFieldLabel();
        qC2b = questionC2b.getValue();
        qC3 = questionC3.getValue().toString();
        qC4a = questionC4a.getValue().getFieldLabel();
        qC4b1 = q4b1.getValue();
        qC4b2 = q4b2.getValue();

        WicidXML wicidxml = new WicidXML("subsidyRequirements");
        wicidxml.addElement("qC2a", qC2a);
        wicidxml.addElement("qC2b", qC2b);
        wicidxml.addElement("qC3", qC3);
        wicidxml.addElement("qC4a", qC4a);
        wicidxml.addElement("qC4b1", qC4b1);
        wicidxml.addElement("qC4b2", qC4b2);
        subsidyRequirementsData = wicidxml.getXml();
    }

    public void setDocumentInfo(){

    }

    public void show() {
        subsidyRequirementsDialog.show();
    }

    public void setOldSubsdyRequirements(OutcomesAndAssessmentOne oldOutcomesAndAssessmentOne) {
        this.oldOutcomesAndAssessmentOne = oldOutcomesAndAssessmentOne;
    }

    /*public void setRulesAndSyllabus(RulesAndSyllabus rulesAndSyllabus) {
    this.rulesAndSyllabus = rulesAndSyllabus;
    }*/
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

package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.event.ButtonEvent;
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
import com.extjs.gxt.ui.client.widget.form.TextField;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.Response;
import org.wits.client.Constants;

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
    private Button addButton = new Button("Add school/entity");
    private Button backButton = new Button("Back");
    private TextArea questionC1 = new TextArea();
    private NumberField questionC3 = new NumberField();
    private MultiField questionC4b = new MultiField();
    private Radio radioC4a1 = new Radio();
    private Radio radioC4a2 = new Radio();
    private Radio radioC2a1 = new Radio();
    private TextArea questionC2b = new TextArea();
    private TextArea q4b1 = new TextArea();
    private TextArea q4b2 = new TextArea();
    private final TextField<String> E1a = new TextField<String>();
    private RulesAndSyllabusTwo rulesAndSyllabusTwo;
    private OutcomesAndAssessmentOne outcomesAndAssessmentOne;
    private OutcomesAndAssessmentOne oldOutcomesAndAssessmentOne;
    //private SubsidyRequirements oldSubsidyRequirements;
    private String subsidyRequirementsData;
    private String qc2a1,qc3,qc4a1;

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

        radioC2a1.enableEvents(true);

        /*
        if (radioC2a1.getValue() == true){
        questionC2b.enable();
        }
        else if (radioC2a2.getValue() == true){
        questionC2b.disable();

        }
         */

        RadioGroup questionC2a = new RadioGroup();
        questionC2a.setFieldLabel("C.2.a. The course/unit is taught");
        questionC2a.add(radioC2a1);
        questionC2a.add(radioC2a2);
        mainForm.add(questionC2a, formData);



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
        radioC4a1.setValue(true);

        radioC4a2.setBoxLabel("No");


        RadioGroup questionC4a = new RadioGroup();
        questionC4a.setFieldLabel("C.4.a. Is any other School/Entity involved in teaching this unit?");
        questionC4a.add(radioC4a1);
        questionC4a.add(radioC4a2);


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

        addButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {


            }
        });


        //function to ensure that all the fields are filled and the form is
        //completed before the user moves to the next form
        // private String qc2a1,qc3,qc4a1,qc3
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                // = questionC1.getValue();// deptField.getValue().getId();
                qc2a1 = radioC2a1.getValue().toString().replaceAll(" ", "--");
                if ((radioC2a1.getValue() == true) && (questionC2b.getValue() == null)) {
                    MessageBox.info("Missing answer", "Provide an answer to C.2.b", null);
                    return;
                }

              
                if (questionC3.getValue() == null) {
                    MessageBox.info("Missing answer", "Provide an answer to C.3.", null);
                    return;
                }
                qc3 = questionC3.getValue().toString().replaceAll(" ", "--");
                

                qc4a1=radioC4a1.getValue().toString().replaceAll(" ", "--");
                if ((radioC4a1.getValue() == true) && (questionC4b.getValue() == null)) {
                    MessageBox.info("Missing answer", "Provide an answer to C.4.b.", null);
                    return;
                }

                qc3 = questionC3.getValue().toString().replaceAll(" ", "--");
                if (questionC3.getRawValue().length() != 6) {
                    MessageBox.info("Error", "The CESM category must be a 6 digit number", null);
                    return;
                }

                if (radioC4a1 .getBoxLabel().toString() == null){
                    
                }
                
                if(q4b1.getValue().toString() == null){
                    MessageBox.info("Error", "please enter a value between 1 and 100", null);
                    return;
                }

                String temp = q4b2.getValue().toString().replaceAll(" ", "--");

                if(temp == null){
                    MessageBox.info("Error", "The CESM category must be a 6 digit number", null);
                    return;

                }

                int q4b2Value = Integer.parseInt(temp);
                if ((q4b2Value > 100) || (q4b2Value < 1)) {
                    MessageBox.info("Error", "please enter a value between 1 and 100", null);
                    return;

                }
                

                /* if (radioC4a1.getValue() == true) {

                String[] q4b1No;
                String[] q4b2No;
                int x = 0;
                int total = 0;

                try {
                q4b1No = /*new String[(*/ /*(q4b1.getValue()).split("\n")/*"\r\n|\r|\n")/*).length]*/
                //  int z = 0;
                // while (z)

                /* } catch (NullPointerException npe) {
                MessageBox.info("Missing answer", "Provide an answer to C.4.b (School/Entity)", null);
                return;
                }

                try {
                q4b2No = q4b2.getValue().split("\r\n|\r|\n");
                } catch (NullPointerException e) {
                MessageBox.info("Missing answer", "Provide an answer to C.4.b (percentage)", null);
                return;
                }

                if (q4b1No.length > q4b2No.length) {
                MessageBox.info("Error", "Missing a percentage of a School or Entity", null);
                return;
                }
                if (q4b2No.length > q4b1No.length) {
                MessageBox.info("Error", "Missing a name of a School or Entity", null);
                return;
                }



                while (x <= q4b2No.length) {
                try {
                int ques4b2 = 0;
                try {
                ques4b2 = Integer.parseInt(q4b2No[x]);
                total = total + ques4b2;
                } catch (NumberFormatException nfe) {
                MessageBox.info("Error", "Please use a percentage.", null);
                }


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
                x++;
                return;
                }
                }

                if (total != 100) {
                MessageBox.info("Error", "The percentages must add up to 100", null);
                return;
                }
                }*/

                String qA1 = "qA1", qA2 = "qA2", qA3 = "qA2", qA4 = "qA2", qA5 = "qA5";
                subsidyRequirementsData = qA1 + "_" + qA2 + "_" + qA3 + "_" + qA4 + "_" + qA5;

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
            }
        });


        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);


        subsidyRequirementsDialog.setBodyBorder(false);
        subsidyRequirementsDialog.setHeading("Section C: Subsidy Requirements");
        subsidyRequirementsDialog.setWidth(750);
        subsidyRequirementsDialog.setHeight(570);
        subsidyRequirementsDialog.setHideOnButtonClick(true);
        subsidyRequirementsDialog.setButtons(Dialog.CLOSE);
        subsidyRequirementsDialog.setButtonAlign(HorizontalAlignment.LEFT);

        subsidyRequirementsDialog.add(mainForm);


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

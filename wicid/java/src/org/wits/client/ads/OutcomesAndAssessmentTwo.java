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
import com.extjs.gxt.ui.client.widget.form.CheckBox;
import com.extjs.gxt.ui.client.widget.form.CheckBoxGroup;
import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.LabelField;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.core.client.GWT;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.RequestException;
import com.google.gwt.http.client.Response;
import org.wits.client.Constants;
import org.wits.client.Document;
import org.wits.client.EditDocumentDialog;

/**
 *
 * @author Jacqueline
 */
public class OutcomesAndAssessmentTwo {

    private Dialog outcomesAndAssessmentTwoDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private OutcomesAndAssessmentTwo outcomesAndAssessmentTwo;
    private CheckBoxGroup questionD4 = new CheckBoxGroup();
    private OutcomesAndAssessmentThree oldOutcomesAndAssessmentThree;
    private OutcomesAndAssessmentOne outcomesAndAssessmentOne;
    private OutcomesAndAssessmentOne oldOutcomesAndAssessmentOne;
    private String outcomesAndAssessmentTwoData;

    public OutcomesAndAssessmentTwo(OutcomesAndAssessmentOne outcomesAndAssessmentOne) {
        this.outcomesAndAssessmentOne = outcomesAndAssessmentOne;
        createUI();
    }

    public OutcomesAndAssessmentTwo(OutcomesAndAssessmentThree oldOutcomesAndAssessmentThree) {
        this.oldOutcomesAndAssessmentThree = oldOutcomesAndAssessmentThree;
    }

    public OutcomesAndAssessmentTwo(){
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(530);
        mainForm.setWidth(800);
        mainForm.setLabelWidth(250);

        /*final CheckBox test = new CheckBox();
        test.setPagePosition(410,200);
        test.setBoxLabel("Test the text shows");
        test.setFieldLabel("this is a test button to ensure that it selects the data");
        mainForm.add(test, formData);
        */

        CheckBox questionD4_1 = new CheckBox();
        questionD4_1.setPagePosition(250, 125);
        //questionD4_1.enable();
        LabelField D41 = new LabelField();
        D41.setText("Identify and solve problems in which responses display "
                + "that responsible decisions using critical and creative thinking have been made.");
        D41.setPagePosition(350, 400);
        D41.setWidth(500);

        CheckBox questionD4_2 = new CheckBox();
        questionD4_2.setPagePosition(250, 163);
        LabelField D42 = new LabelField();
        D42.setText("Work effectively with others as a member of a team, "
                + "group, organisation, community.");
        D42.setPagePosition(410, 200);
        D42.setWidth(500);

        CheckBox questionD4_3 = new CheckBox();
        questionD4_3.setPagePosition(250, 186);
        LabelField D43 = new LabelField();
        D43.setText("Organise and manage oneself and one’s activities "
                + "responsibly and effectively.");
        D43.setPagePosition(310, 130);
        D43.setWidth(500);


        CheckBox questionD4_4 = new CheckBox();
        questionD4_4.setPagePosition(250, 208);
        LabelField D44 = new LabelField();
        D44.setText("Collect, analyse, organise and critically evaluate "
                + "information.");
        D44.setPagePosition(310, 150);
        D44.setWidth(500);

        CheckBox questionD4_5 = new CheckBox();
        questionD4_5.setPagePosition(250, 232);
        LabelField D45 = new LabelField();
        D45.setText("Communicate effectively using visual, mathematical and/or"
                + " language skills in the modes of oral and/ or written presentation.");
        D45.setPagePosition(310, 170);
        D45.setWidth(500);

        CheckBox questionD4_6 = new CheckBox();
        questionD4_6.setPagePosition(250, 268);
        LabelField D46 = new LabelField();
        D46.setText("Use science and technology effectively and critically, "
                + "showing responsibility towards the environment and health of others.");
        D46.setPagePosition(310, 190);
        D46.setWidth(500);

        CheckBox questionD4_7 = new CheckBox();
        questionD4_7.setPagePosition(250, 306);
        LabelField D47 = new LabelField();
        D47.setText("Demonstrate an understanding of the world as a set of "
                + "related systems by recognising that problem-solving contexts do not exist "
                + "in isolation.");
        D47.setPagePosition(310, 210);
        D47.setWidth(500);

        CheckBox questionD4_8 = new CheckBox();
        questionD4_8.setPagePosition(250, 342);
        LabelField D48 = new LabelField();
        D48.setText("In order to contribute to the full personal development "
                + "of each learner and the social economic development of the society at large, "
                + "it must be the intention underlying any programme of learning to make an "
                + "individual aware of the importance of:");
        LabelField D48_1 = new LabelField("-   Reflecting on and exploring a variety of strategies to learn more effectively;");
        LabelField D48_2 = new LabelField("-   Participating as responsible citizens in the life of local, national and global communities;");
        LabelField D48_3 = new LabelField("-   Being culturally and aesthetically sensitive across a range of social contexts;");
        LabelField D48_4 = new LabelField("-   Exploring education and career opportunities; and");
        LabelField D48_5 = new LabelField("-   Developing entrepreneurial opportunities.");
        D48.setPagePosition(310, 230);
        D48.setWidth(500);



        questionD4.setFieldLabel("D.4. Specify the critical cross-field outcomes "
                + "(CCFOs) integrated into the course/unit using the list provided.");
        questionD4.add(questionD4_1);
        questionD4.add(questionD4_2);
        questionD4.add(questionD4_3);
        questionD4.add(questionD4_4);
        questionD4.add(questionD4_5);
        questionD4.add(questionD4_6);
        questionD4.add(questionD4_7);
        questionD4.add(questionD4_8);


        mainForm.add(questionD4, formData);
        mainForm.add(D41, formData);
        mainForm.add(D42, formData);
        mainForm.add(D43, formData);
        mainForm.add(D44, formData);
        mainForm.add(D45, formData);
        mainForm.add(D46, formData);
        mainForm.add(D47, formData);
        mainForm.add(D48, formData);
        mainForm.add(D48_1, formData);
        mainForm.add(D48_2, formData);
        mainForm.add(D48_3, formData);
        mainForm.add(D48_4, formData);
        mainForm.add(D48_5, formData);


        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        //function to ensure that all the fields are filled and the form is
        //completed before the user moves to the next form
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {


            @Override
            public void componentSelected(ButtonEvent ce) {

                /*if (test.getValue() == null) {
                    MessageBox.info("Missing answer", "Provide at least one selection to question D.4", null);
                    return;
                }*/

                String qA1 = "qA1", qA2 = "qA2", qA3 = "qA2", qA4 = "qA2", qA5 = "qA5";
                outcomesAndAssessmentTwoData = qA1 + "_" + qA2 + "_" + qA3 + "_" + qA4 + "_" + qA5;

                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname=" + "outcomesandassessmenttwo" + "&formdata=" + outcomesAndAssessmentTwoData + "&docid=" + Constants.docid;

                createDocument(url);

                if(oldOutcomesAndAssessmentThree == null){

                    OutcomesAndAssessmentThree outcomesAndAssessment2 = new OutcomesAndAssessmentThree(OutcomesAndAssessmentTwo.this);
                    outcomesAndAssessment2.show();
                    outcomesAndAssessmentTwoDialog.hide();
                }else{
                    oldOutcomesAndAssessmentThree.show();
                    outcomesAndAssessmentTwoDialog.hide();
                }
            }
        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                outcomesAndAssessmentOne.setOldOutComesAndAssessmentOne(OutcomesAndAssessmentTwo.this);
                outcomesAndAssessmentOne.show();
                outcomesAndAssessmentTwoDialog.hide();
            }
        });

        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        outcomesAndAssessmentTwoDialog.setBodyBorder(false);
        outcomesAndAssessmentTwoDialog.setHeading("Section D: Outcomes and Assessment - Page Two");
        outcomesAndAssessmentTwoDialog.setWidth(800);
        outcomesAndAssessmentTwoDialog.setHeight(600);
        outcomesAndAssessmentTwoDialog.setHideOnButtonClick(true);
        outcomesAndAssessmentTwoDialog.setButtons(Dialog.CLOSE);
        outcomesAndAssessmentTwoDialog.setButtonAlign(HorizontalAlignment.LEFT);
        outcomesAndAssessmentTwoDialog.setHideOnButtonClick(true);

        outcomesAndAssessmentTwoDialog.add(mainForm);

        //setDepartment();
    }

    public void show() {
        outcomesAndAssessmentTwoDialog.show();
    }

    public void setOldOutcomesAndAssessmentTwo(OutcomesAndAssessmentThree oldOutcomesAndAssessmentThree) {
        this.oldOutcomesAndAssessmentThree = oldOutcomesAndAssessmentThree;
    }


    /*  public void setSelectedFolder(ModelData selectedFolder) {
    this.selectedFolder = selectedFolder;
    topicField.setValue((String) this.selectedFolder.get("id"));
    topicField.setToolTip((String) this.selectedFolder.get("id"));
    }
     */
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

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
import com.extjs.gxt.ui.client.widget.form.NumberField;
import com.google.gwt.user.client.ui.Grid;
import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.layout.BorderLayoutData;
import com.extjs.gxt.ui.client.widget.layout.FormData;
import com.google.gwt.http.client.Request;
import com.google.gwt.http.client.RequestBuilder;
import com.google.gwt.http.client.RequestCallback;
import com.google.gwt.http.client.Response;
import com.extjs.gxt.ui.client.widget.Label;
import com.google.gwt.core.client.GWT;
import com.google.gwt.event.dom.client.ClickEvent;
import com.google.gwt.event.dom.client.ClickHandler;
import org.wits.client.Constants;

/**
 *
 * @author Jacqueline
 */
public class OutcomesAndAssessmentThree {

    private Dialog outcomesAndAssessmentThreeDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private OutcomesAndAssessmentTwo outcomesAndAssessmentTwo;
    private OutcomesAndAssessmentThree outcomesAndAssessmentThree;
    private double creSAQA = 0;
    private Grid questionD5 = new Grid(14, 2);
    private NumberField numWeeks = new NumberField();
    private NumberField hrsTeaching = new NumberField();
    private NumberField hrsTuts = new NumberField();
    private NumberField hrsLabs = new NumberField();
    private NumberField hrsOther = new NumberField();
    private NumberField hrsStudy = new NumberField();
    private NumberField examsPerYr = new NumberField();
    private NumberField examsLength = new NumberField();
    private NumberField hrsExamPrep = new NumberField();
    private Resources oldResources;
    private OutcomesAndAssessmentTwo oldOutComesAndAssessmentTwo;
    private OutcomesAndAssessmentThree oldOutcomesAndAssessmentThree;
    private String outcomesAndAssessmentThreeData;

    public OutcomesAndAssessmentThree(OutcomesAndAssessmentTwo outcomesAndAssessmentTwo) {
        this.outcomesAndAssessmentTwo = outcomesAndAssessmentTwo;
        createUI();
    }

    public OutcomesAndAssessmentThree(Resources oldResources) {
        this.oldResources = oldResources;
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setHeight(600);
        mainForm.setWidth(800);
        mainForm.setLabelWidth(250);

        Label qD5Label = new Label("D.5. Specify the notional study hours expected for"
                + "the duration of the course/unit using the spreadsheet provided");
        mainForm.add(qD5Label, formData);

        questionD5.setPixelSize(600, 500);
        questionD5.setBorderWidth(1);
        questionD5.setText(0, 0, "a. Over how many weeks will this course run?");
        questionD5.setText(1, 0, "b. How many hours of teaching will a particular "
                + "student experience for this specific course in a single week?");
        questionD5.setText(2, 0, "c. How many hours of tutorials will a particular "
                + "student experience for this specific course in a single week?");
        questionD5.setText(3, 0, "d. How many lab hours will a particular student "
                + "experience for this specific course in a single week? (Note the "
                + "assumption is that there is only one staff contact hour per lab, the "
                + "remaining lab time is student self-study)");
        questionD5.setText(4, 0, "e. How many other contact sessions are there each "
                + "week including periods used for tests or other assessments w hich "
                + "have not been included in the number of lecture, tutorial or laboratory "
                + "sessions.");
        questionD5.setText(5, 0, "Total contact time");
        questionD5.setText(6, 0, "f. For every hour of lectures or contact with a "
                + "staff member, how many hours should the student spend studying by her-/himself?");
        questionD5.setText(7, 0, "Total notional study hours (excluding the exams)");
        questionD5.setText(8, 0, "g. How many exams are there per year?");
        questionD5.setText(9, 0, "h. How long is each exam?");
        questionD5.setText(10, 0, "Total exam time per year");
        questionD5.setText(11, 0, "i. How many hours of preparation for the exam is "
                + "the student expected to undertake?");
        questionD5.setText(12, 0, "Total notional study hours");
        questionD5.setText(13, 0, "Total SAQA Credits");

        numWeeks.setAllowBlank(false);
        numWeeks.setValue(0);

        hrsTeaching.setAllowBlank(false);
        hrsTeaching.setValue(0);

        hrsTuts.setAllowBlank(false);
        hrsTuts.setValue(0);

        hrsLabs.setAllowBlank(false);
        hrsLabs.setValue(0);

        hrsOther.setAllowBlank(false);
        hrsOther.setValue(0);

        hrsStudy.setAllowBlank(false);
        hrsStudy.setValue(0);

        examsPerYr.setAllowBlank(false);
        examsPerYr.setValue(0);

        examsLength.setAllowBlank(false);
        examsLength.setValue(0);

        hrsExamPrep.setAllowBlank(false);
        hrsExamPrep.setValue(0);

        questionD5.setWidget(0, 1, numWeeks);
        questionD5.setWidget(1, 1, hrsTeaching);
        questionD5.setWidget(2, 1, hrsTuts);
        questionD5.setWidget(3, 1, hrsLabs);
        questionD5.setWidget(4, 1, hrsOther);
        questionD5.setWidget(6, 1, hrsStudy);
        questionD5.setWidget(8, 1, examsPerYr);
        questionD5.setWidget(9, 1, examsLength);
        questionD5.setWidget(11, 1, hrsExamPrep);


        questionD5.addClickHandler(new ClickHandler() {

            @Override
            public void onClick(ClickEvent event) {
                int conTime = (hrsTeaching.getValue().intValue() + hrsTuts.getValue().intValue() + hrsLabs.getValue().intValue() + hrsOther.getValue().intValue()) * numWeeks.getValue().intValue();
                String contactTime = Integer.toString(conTime);

                int stdyHours = conTime + (conTime * hrsStudy.getValue().intValue());
                String studyHours = Integer.toString(stdyHours);

                int exTime = examsLength.getValue().intValue() * examsPerYr.getValue().intValue();
                String examTime = Integer.toString(exTime);

                int notStudyHrs = stdyHours + exTime + hrsExamPrep.getValue().intValue();
                String notionalStudyHours = Integer.toString(notStudyHrs);


                creSAQA = notStudyHrs / 10;
                String creditsSAQA = Double.toString(creSAQA);


                questionD5.setText(5, 1, contactTime);
                questionD5.setText(7, 1, studyHours);
                questionD5.setText(10, 1, examTime);
                questionD5.setText(12, 1, notionalStudyHours);
                questionD5.setText(13, 1, creditsSAQA);

            }
        });

        mainForm.add(questionD5, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        //function to ensure that all the fields are filled and the form is
        //completed before the user moves to the next form
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {


            @Override
            public void componentSelected(ButtonEvent ce) {

                if (creSAQA == 0) {
                    MessageBox.info("Missing answer", "Please fill in the table", null);
                    return;
                }

                String qA1 = "qA1", qA2 = "qA2", qA3 = "qA2", qA4 = "qA2", qA5 = "qA5";
                outcomesAndAssessmentThreeData = qA1 + "_" + qA2 + "_" + qA3 + "_" + qA4 + "_" + qA5;

                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname=" + "outcomesandassessmentthree" + "&formdata=" + outcomesAndAssessmentThreeData + "&docid=" + Constants.docid;

                createDocument(url);
                if (oldResources == null) {

                    Resources resources = new Resources(OutcomesAndAssessmentThree.this);
                    resources.show();
                    outcomesAndAssessmentThreeDialog.hide();
                } else {
                    oldResources.show();
                    outcomesAndAssessmentThreeDialog.hide();
                }

            }
        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                outcomesAndAssessmentTwo.setOldOutcomesAndAssessmentTwo(OutcomesAndAssessmentThree.this);
                outcomesAndAssessmentTwo.show();
                outcomesAndAssessmentThreeDialog.hide();
            }
        });

        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        outcomesAndAssessmentThreeDialog.setBodyBorder(false);
        outcomesAndAssessmentThreeDialog.setHeading("Section D: Outcomes and Assessment - Page Three");
        outcomesAndAssessmentThreeDialog.setWidth(800);
        outcomesAndAssessmentThreeDialog.setHeight(670);
        outcomesAndAssessmentThreeDialog.setHideOnButtonClick(true);
        outcomesAndAssessmentThreeDialog.setButtons(Dialog.CLOSE);
        outcomesAndAssessmentThreeDialog.setButtonAlign(HorizontalAlignment.LEFT);
        outcomesAndAssessmentThreeDialog.setHideOnButtonClick(true);

        outcomesAndAssessmentThreeDialog.add(mainForm);

        //setDepartment();
    }

    public void show() {
        outcomesAndAssessmentThreeDialog.show();
    }

    public void setOldOutcomesAndAssessmentThree(Resources oldResources) {
        this.oldResources = oldResources;
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

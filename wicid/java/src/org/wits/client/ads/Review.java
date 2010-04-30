package org.wits.client.ads;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.button.Button;

import com.extjs.gxt.ui.client.widget.form.FormPanel;
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
 * @author nguni
 */
public class Review {

    private Dialog newReviewDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private final TextArea G1a = new TextArea();
    private final TextArea G1b = new TextArea();
    private final TextArea G2a = new TextArea();
    private final TextArea G2b = new TextArea();
    private final TextArea G3a = new TextArea();
    private final TextArea G3b = new TextArea();
    private final TextArea G4a = new TextArea();
    private final TextArea G4b = new TextArea();
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private CollaborationAndContracts collaborationAndContracts;
    private ContactDetails contactDetails;
    private CollaborationAndContracts oldCollaborationAndContracts;
    private Review oldReview;
    private ContactDetails oldContactDetails;
    private String reviewData;

    public Review(CollaborationAndContracts collaborationAndContracts) {
        this.collaborationAndContracts = collaborationAndContracts;
        createUI();
    }

    public Review(ContactDetails contactDetails) {
        this.contactDetails = contactDetails;
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(700);
        mainForm.setLabelWidth(400);

        G1a.setFieldLabel("G.1.a How will the course/unit syllabus be reviewed?");
        G1a.setAllowBlank(false);
        G1a.setPreventScrollbars(false);
        G1a.setHeight(50);
        G1a.setName("G1a");

        G1b.setFieldLabel("G.1.b  How often will the course/unit syllabus be reviewed?");
        G1b.setAllowBlank(false);
        G1b.setPreventScrollbars(false);
        G1b.setHeight(50);
        G1b.setName("G1b");

        G2a.setFieldLabel("G.2.a How will the integration of course/unit outcomes, syllabus, teaching methods and assessment methods be evaluated? ");
        G2a.setAllowBlank(false);
        G2a.setPreventScrollbars(false);
        G2a.setHeight(50);
        G2a.setName("G2a");

        G2b.setFieldLabel("G.2.b How often will the above integration be evaluated?");
        G2b.setAllowBlank(false);
        G2b.setPreventScrollbars(false);
        G2b.setHeight(50);
        G2b.setName("G2b");

        G3a.setFieldLabel("G.3.a How will the course/unit through-put rate be evaluated?");
        G3a.setAllowBlank(false);
        G3a.setPreventScrollbars(false);
        G3a.setHeight(50);
        G3a.setName("G3a");

        G3b.setFieldLabel("G.3.b How often will the course/unit through-put rate be evaluated?");
        G3b.setAllowBlank(false);
        G3b.setPreventScrollbars(false);
        G3b.setHeight(50);
        G3b.setName("G3b");

        G4a.setFieldLabel("G.4.a How will the teaching on the course/unit be evaluated from a student perspective and from the lecturer’s perspective?");
        G4a.setAllowBlank(false);
        G4a.setPreventScrollbars(false);
        G4a.setHeight(50);
        G4a.setName("G4a");

        G4b.setFieldLabel("G.4.b How often will the teaching on the course/unit be evaluated from these two perspectives?");
        G4b.setAllowBlank(false);
        G4b.setPreventScrollbars(false);
        G4b.setHeight(50);
        G4b.setName("G4b");

        mainForm.add(G1a, formData);
        mainForm.add(G1b, formData);
        mainForm.add(G2a, formData);
        mainForm.add(G2b, formData);
        mainForm.add(G3a, formData);
        mainForm.add(G3b, formData);
        mainForm.add(G4a, formData);
        mainForm.add(G4b, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();
                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname="+"review"+"&formdata=" +reviewData+"&docid="+Constants.docid;
                        //+ "&department=" + dept + "&telephone=" + telephone
                        //+ "&topic=" + topic + "&title=" + title + "&mode=" + Constants.main.getMode();


                createDocument(url);
                if(oldContactDetails == null){
                    
                    ContactDetails contactDetails = new ContactDetails(Review.this);
                    contactDetails.show();
                    newReviewDialog.hide();
                }else{
                    oldContactDetails.show();;
                    newReviewDialog.hide();;
                }
            }

        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                collaborationAndContracts.setOldCollaborationAndContracts(Review.this);
                collaborationAndContracts.show();
                newReviewDialog.hide();
                storeDocumentInfo();

            }
        });

        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        newReviewDialog.setBodyBorder(false);
        newReviewDialog.setHeading("Section G: Review");
        newReviewDialog.setWidth(800);
        //newReviewDialog.setHeight(450);
        newReviewDialog.setHideOnButtonClick(true);
        newReviewDialog.setButtons(Dialog.CLOSE);
        newReviewDialog.setButtonAlign(HorizontalAlignment.LEFT);

        newReviewDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();

            }
        });

        setDocumentInfo();
        newReviewDialog.add(mainForm);
    }

    public void storeDocumentInfo() {
        WicidXML wicidxml = new WicidXML("review");
        wicidxml.addElement("G1a", G1a.getValue());
        wicidxml.addElement("G1b", G1b.getValue());
        wicidxml.addElement("G2a", G2a.getValue());
        wicidxml.addElement("G2b", G2b.getValue());
        wicidxml.addElement("G3a", G3a.getValue());
        wicidxml.addElement("G3b", G3b.getValue());
        wicidxml.addElement("G4a", G4a.getValue());
        wicidxml.addElement("G4b", G4b.getValue());
        reviewData = wicidxml.getXml();
    }

    public void setDocumentInfo(){

    }

    public void show() {
        newReviewDialog.show();
    }

    public void setOldReview(ContactDetails oldContactDetails){
        this.oldContactDetails = oldContactDetails;

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

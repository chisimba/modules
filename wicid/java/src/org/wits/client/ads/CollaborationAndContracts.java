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
 * @author nguni
 */
public class CollaborationAndContracts {

    private Dialog newCollaborationAndContractsDialog = new Dialog();
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private final RadioGroup F1a = new RadioGroup();
    private final Radio radioNo1 = new Radio();
    private final Radio radioYes1 = new Radio();
    private final Radio radioNo2 = new Radio();
    private final Radio radioYes2 = new Radio();
    private final TextArea F1b = new TextArea();
    private final RadioGroup F2a = new RadioGroup();
    private final TextArea F2b = new TextArea();
    private final TextArea F3a = new TextArea();
    private final TextArea F3b = new TextArea();
    private final TextArea F4 = new TextArea();
    private Button saveButton = new Button("Next");
    private Button backButton = new Button("Back");
    private Resources resources;
    private Review review;
    private Review oldReview;
    private CollaborationAndContracts oldCollaborationAndContracts;
    private String collaborationAndContractsData;
    private String qF1a, qF2a, qF2b, qF3a, qF3b, qF4;

    public CollaborationAndContracts(Resources resources) {
        this.resources = resources;
        createUI();
    }

    public CollaborationAndContracts(Review review) {
        this.review = review;
        createUI();
    }

    private void createUI() {

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(700);
        mainForm.setLabelWidth(400);

        radioNo1.setBoxLabel("No");
        radioYes1.setBoxLabel("Yes");
        radioYes1.setValue(true);
        radioNo2.setBoxLabel("No");
        radioYes2.setBoxLabel("Yes");
        radioYes2.setValue(true);

        F1a.setFieldLabel("F.1.a Is approval for the course/unit required from a professional body?");
        F1a.add(radioYes1);
        F1a.add(radioNo1);

        F1b.setFieldLabel("F.1.b If yes, state the name of the professional body and provide details of the body's prerequisites and/or contraints.");
        F1b.setAllowBlank(false);
        F1b.setPreventScrollbars(false);
        F1b.setHeight(50);
        F1b.setName("F1b");

        F2a.setFieldLabel("F.2.a Are other Schools or Faculties involved in and/or have an interest in the course/unit?");
        F2a.add(radioYes2);
        F2a.add(radioNo2);

        F2b.setFieldLabel("F.2.b If yes, provide the details of the other School's or Faculties's involvement/interest, including support and provisioning for the course/unit.");
        F2b.setAllowBlank(false);
        F2b.setPreventScrollbars(false);
        F2b.setHeight(50);
        F2b.setName("F2b");

        F3a.setFieldLabel("F.3.a Does the course/unit provide service learning?");
        F3a.setAllowBlank(false);
        F3a.setPreventScrollbars(false);
        F3a.setHeight(50);
        F3a.setName("F3a");

        F3b.setFieldLabel("F.3.b If yes, provide the details on the nature as well as the provisioning for the service learning component and methodology.");
        F3b.setAllowBlank(false);
        F3b.setPreventScrollbars(false);
        F3b.setHeight(50);
        F3b.setName("F3b");

        F4.setFieldLabel("F.4 Specify whether collaboratoin, contracts or other cooperatoin agreements have been, or will need to be, entered into with entities outside of the univerty?");
        F4.setAllowBlank(false);
        F4.setPreventScrollbars(false);
        F4.setHeight(50);
        F4.setName("F4");

        mainForm.add(F1a, formData);
        mainForm.add(F1b, formData);
        mainForm.add(F2a, formData);
        mainForm.add(F2b, formData);
        mainForm.add(F3a, formData);
        mainForm.add(F3b, formData);
        mainForm.add(F4, formData);

        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));


        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

                if(F1a.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
                qF1a = F1a.getValue().getBoxLabel().toString();

                 if(F1a.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
                qF1a = F1a.getValue().getBoxLabel().toString();

                if(F2a.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
                qF2a = F2a.getValue().getBoxLabel().toString();

                 if(F2b.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
                qF2b = F2b.getValue().toString();

                 if(F3a.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
                qF3a = F3a.getValue().toString();

                 if(F3b.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
                qF3b = F3b.getValue().toString();

                 if(F4.getValue() == null){
                    MessageBox.info("Missing Selection", "Please make a selection to question F1a", null);
                    return;
                }
               

                storeDocumentInfo();

                String url =
                        GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                        + "?module=wicid&action=saveFormData&formname=" + "collaborationandcontracts" + "&formdata=" + collaborationAndContractsData + "&docid=" + Constants.docid;

                createDocument(url);

                if (oldReview == null) {

                    Review review = new Review(CollaborationAndContracts.this);
                    review.show();
                    newCollaborationAndContractsDialog.hide();

                }else{
                    oldReview.show();
                    newCollaborationAndContractsDialog.hide();
                }
            }
        });

        backButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                resources.setOldResources(CollaborationAndContracts.this);
                resources.show();
                newCollaborationAndContractsDialog.hide();
                storeDocumentInfo();
            }
        });

        mainForm.addButton(backButton);
        mainForm.addButton(saveButton);
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);

        newCollaborationAndContractsDialog.setBodyBorder(false);
        newCollaborationAndContractsDialog.setHeading("Section F: Collaboration and Contracts");
        newCollaborationAndContractsDialog.setWidth(800);
        //newCollaborationAndContractsDialog.setHeight(450);
        newCollaborationAndContractsDialog.setHideOnButtonClick(true);
        newCollaborationAndContractsDialog.setButtons(Dialog.CLOSE);
        newCollaborationAndContractsDialog.setButtonAlign(HorizontalAlignment.LEFT);

        newCollaborationAndContractsDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {
            @Override
            public void componentSelected(ButtonEvent ce) {
                storeDocumentInfo();
            }
        });

        getDocumentInfo();
        newCollaborationAndContractsDialog.add(mainForm);
    }

    public void storeDocumentInfo() {
          
        WicidXML wicidxml = new WicidXML("CollaborationAndContracts");
        wicidxml.addElement("F1a", F1a.getValue().toString());
        wicidxml.addElement("F1b", F1b.getValue());
        wicidxml.addElement("F2a", F2a.getValue().toString());
        wicidxml.addElement("F2b", F2b.getValue());
        wicidxml.addElement("F3a", F3a.getValue());
        wicidxml.addElement("F3b", F3b.getValue());
        wicidxml.addElement("F4", F4.getValue());
        collaborationAndContractsData = wicidxml.getXml();
    }

    public void getDocumentInfo(){

    }

    public void show() {
        newCollaborationAndContractsDialog.show();
    }

    /*public void setOldReview(Review oldReview) {
        this.oldReview = oldReview;
    }*/

    public void setOldCollaborationAndContracts(Review oldReview) {
        this.oldReview = oldReview;
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

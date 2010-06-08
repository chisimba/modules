/*
 * @author: Nguni Phakela
 * 
 */
package org.wits.client;

import com.extjs.gxt.ui.client.Style.HorizontalAlignment;
import com.extjs.gxt.ui.client.Style.LayoutRegion;
import com.extjs.gxt.ui.client.data.ModelData;
import com.extjs.gxt.ui.client.event.ButtonEvent;
import com.extjs.gxt.ui.client.event.SelectionListener;
import com.extjs.gxt.ui.client.event.WindowEvent;
import com.extjs.gxt.ui.client.store.ListStore;
import com.extjs.gxt.ui.client.util.Margins;
import com.extjs.gxt.ui.client.widget.Dialog;
import com.extjs.gxt.ui.client.widget.Label;
import com.extjs.gxt.ui.client.widget.MessageBox;
import com.extjs.gxt.ui.client.widget.Window;

import com.extjs.gxt.ui.client.widget.button.Button;
import com.extjs.gxt.ui.client.widget.form.ComboBox;
import com.extjs.gxt.ui.client.widget.form.ComboBox.TriggerAction;
import com.extjs.gxt.ui.client.widget.form.DateField;
import com.extjs.gxt.ui.client.widget.form.FileUploadField;


import com.extjs.gxt.ui.client.widget.form.FormPanel;
import com.extjs.gxt.ui.client.widget.form.FormPanel.Encoding;
import com.extjs.gxt.ui.client.widget.form.FormPanel.Method;
import com.extjs.gxt.ui.client.widget.form.LabelField;
import com.extjs.gxt.ui.client.widget.form.Radio;
import com.extjs.gxt.ui.client.widget.form.RadioGroup;
import com.extjs.gxt.ui.client.widget.form.TextArea;
import com.extjs.gxt.ui.client.widget.form.TextField;

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
import com.google.gwt.user.client.ui.Grid;
import com.extjs.gxt.ui.client.event.WindowListener;
import java.util.ArrayList;

import java.util.Date;
import java.util.List;
import org.wits.client.ads.OverView;
import org.wits.client.util.WicidXML;
import java.util.Date;

/**
 *
 * @author davidwaf
 */
public class EditDocumentDialog {

    private Dialog editDocumentDialog = new Dialog();
    private ModelData selectedFolder;
    private FormPanel mainForm = new FormPanel();
    private FormData formData = new FormData("-20");
    private DateTimeFormat fmt = DateTimeFormat.getFormat("y/M/d");
    private final TextField<String> titleField = new TextField<String>();
    private final TextField<String> deptField = new TextField<String>();
    private final TextField<String> telField = new TextField<String>();
    private final TextField<String> numberField = new TextField<String>();
    private final TextField<String> ownerField = new TextField<String>();
    private Button saveButton = new Button("Save");
    private Button browseTopicsButton = new Button("Browse Facuties");
    private TopicListingFrame topicListingFrame;
    private TextArea topicField = new TextArea();
    private Dialog topicListingDialog = new Dialog();
    private Document document;
    private FormPanel uploadpanel = new FormPanel();
    private Button uploadButton = new Button("Add attachment");
    private Button uploadIcon;
    private ComboBox<Group> groupField = new ComboBox<Group>();
    private Label namesField = new Label();
    private String mode;
    private Main main;
    private LabelField uploadFile = new LabelField();
    private Grid upload = new Grid(2, 1);
    private OverView overView;
    private Button nextButton = new Button("Next");
    private boolean myResult;
    private BorderLayoutData uploadEastData = new BorderLayoutData(LayoutRegion.EAST, 60);
    private BorderLayoutData uploadWestData = new BorderLayoutData(LayoutRegion.WEST, 60);
    private BorderLayoutData uploadCenterData = new BorderLayoutData(LayoutRegion.CENTER, 60);

    public EditDocumentDialog(Document document, String mode, Main main) {
        this.document = document;
        this.mode = mode;
        this.main = main;
        Constants.docid=document.getId();
        createUI();

    }

    private void createUI() {
        uploadWestData.setMargins(new Margins(0));
        uploadCenterData.setSplit(true);
        uploadCenterData.setMargins(new Margins(0, 0, 0, 5));

        mainForm.setFrame(false);
        mainForm.setBodyBorder(false);
        mainForm.setWidth(480);


        final DateField dateField = new DateField();
        dateField.setFieldLabel("Entry date");
        dateField.getPropertyEditor().setFormat(fmt);
        dateField.setName("datefield");
        dateField.setEditable(false);
        dateField.setAllowBlank(false);

        ListStore<Group> groupStore = new ListStore<Group>();
        List<Group> groups = new ArrayList<Group>();
        groups.add(new Group("Public"));
        groups.add(new Group("Council"));
        groups.add(new Group("Administration"));
        groupStore.add(groups);

        groupField.setFieldLabel("Group");
        groupField.setName("groupField");
        groupField.setDisplayField("name");
        groupField.setEmptyText("Select group ..");
        groupField.setValue(new Group(document.getGroup()));
        groupField.setTriggerAction(TriggerAction.ALL);
        groupField.setStore(groupStore);
        groupField.setAllowBlank(false);
        groupField.setEditable(false);


        numberField.setFieldLabel("Reference Number");
        numberField.setAllowBlank(false);
        numberField.setEnabled(false);
        numberField.setValue(document.getRefNo());
        numberField.setName("numberfield");
        mainForm.add(numberField, formData);
        topicField.setEnabled(false);

        
        ownerField.setFieldLabel("Owner");
        ownerField.setAllowBlank(false);
        ownerField.setEnabled(false);
        ownerField.setValue(document.getOwnerName());
        ownerField.setName("ownerfield");
        mainForm.add(ownerField, formData);
        
        deptField.setFieldLabel("Originating department");
        deptField.setAllowBlank(false);
        deptField.setValue(document.getDepartment());
        deptField.setName("deptfield");
        mainForm.add(deptField, formData);
        
        telField.setFieldLabel("Tel. Number");
        telField.setValue("edit mode");
        telField.setValue(document.getTelephone());
        telField.setAllowBlank(false);
        telField.setName("telfield");
        mainForm.add(telField, formData);
        
        titleField.setFieldLabel("Document title");
        titleField.setAllowBlank(false);
        titleField.setValue(document.getTitle());
        titleField.setName("titlefield");
        mainForm.add(titleField, formData);
        mainForm.add(groupField, formData);
        BorderLayoutData centerData = new BorderLayoutData(LayoutRegion.CENTER);
        centerData.setMargins(new Margins(0));

        BorderLayoutData eastData = new BorderLayoutData(LayoutRegion.EAST, 150);
        eastData.setSplit(true);
        eastData.setMargins(new Margins(0, 0, 0, 5));
        topicField.setName("Faculty");

        topicField.setValue(document.getTopic());
        topicField.setFieldLabel("Topic");

        FormPanel panel = new FormPanel();
        panel.setSize(400, 70);
        panel.setHeading("Faculty");
        panel.setLayout(new BorderLayout());
        panel.add(topicField, centerData);
        panel.add(browseTopicsButton, eastData);
        mainForm.add(panel, formData);

        browseTopicsButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                if (topicListingFrame == null) {
                    topicListingFrame = new TopicListingFrame(EditDocumentDialog.this);
                    topicListingDialog.setBodyBorder(false);
                    topicListingDialog.setHeading("Topic Listing");
                    topicListingDialog.setWidth(500);
                    topicListingDialog.setHeight(400);
                    topicListingDialog.setHideOnButtonClick(true);
                    topicListingDialog.setButtons(Dialog.CLOSE);
                    topicListingDialog.setButtonAlign(HorizontalAlignment.LEFT);

                    topicListingDialog.add(topicListingFrame);
                }
                topicListingDialog.show();
            }
        });

        nextButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                overView = new OverView(EditDocumentDialog.this);
                overView.show();
                editDocumentDialog.setVisible(false);
            }
        });
        Radio publicOpt = new Radio();
        publicOpt.setBoxLabel("Public");
        publicOpt.setValue(true);

        Radio privateOpt = new Radio();
        privateOpt.setBoxLabel("Private");

        Radio draftOpt = new Radio();
        draftOpt.setBoxLabel("Draft");

        RadioGroup radioGroup = new RadioGroup();
        radioGroup.setFieldLabel("Access");
        radioGroup.add(publicOpt);
        radioGroup.add(privateOpt);
        radioGroup.add(draftOpt);

        uploadpanel.setLayout(new BorderLayout());
        uploadpanel.setHeading("File Upload");
        uploadpanel.setFrame(true);
        uploadpanel.setAction(GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN
                + "?module=wicid&action=doupload&docname=" + document.getTitle() + "&path="
                + document.getTopic() + "&docid=" + document.getId());
        uploadpanel.setEncoding(Encoding.MULTIPART);
        uploadpanel.setMethod(Method.POST);
        uploadpanel.setSize(200, 80);
        uploadpanel.setButtonAlign(HorizontalAlignment.CENTER);

        FileUploadField fileUploadField = new FileUploadField();
        fileUploadField.setName("filenamefield");
        fileUploadField.setFieldLabel("Upload file");
        
        saveButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                if (uploadpanel.isValid()) {

                    uploadpanel.submit();

                }
                Date date = new Date();
                try {
                    if (dateField.getDatePicker() != null) {
                        date = dateField.getDatePicker().getValue();
                    }
                } catch (Exception ex) {
                }

                String dept = deptField.getValue();
                if (dept == null) {
                    MessageBox.info("Missing department", "Provide originating department", null);
                    return;
                }
                if (dept.trim().equals("")) {
                    MessageBox.info("Missing department", "Provide department", null);
                    return;
                }

                String tel = telField.getValue();
                if(tel == null) {
                    MessageBox.info("Missing telephone", "Provide telephone", null);
                    return;
                }

                String title = titleField.getValue();
                if (title == null) {
                    MessageBox.info("Missing title", "Provide title", null);
                    return;
                }
                if (title.trim().equals("")) {
                    MessageBox.info("Missing title", "Provide title", null);
                    return;
                }
                String topic = topicField.getValue();

                if (topic == null) {
                    MessageBox.info("Missing topic", "Provide topic", null);
                    return;
                }
                if (topic.trim().equals("")) {
                    MessageBox.info("Missing topic", "Provide topic", null);
                    return;
                }

                String group = groupField.getValue().getName();
                if (group == null) {
                    MessageBox.info("Missing group", "Select group", null);
                    return;
                }
                if (group.trim().equals("")) {
                    MessageBox.info("Missing group", "Select group", null);
                    return;
                }
                String url = GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + "?"
                        + "module=wicid&action=updatedocument&dept=" + dept + "&topic=" + topic
                        + "&title=" + title + "&tel=" + tel +  "&group=" + group + "&date=" + fmt.format(date) + "&docid=" + document.getId();


                updateDocument(url);
                storeDocumentInfo();


                editDocumentDialog.hide();

            }
        });

        if (mode.equals("apo")) {
            mainForm.addButton(nextButton);
        } else {
            uploadpanel.add(saveButton, uploadWestData);
        }
        
        uploadButton.setIconStyle("add16");
        if (mode.equals("default")) {
            uploadpanel.add(uploadButton, uploadCenterData);
            if(document.getAttachmentStatus().length() > 3 ) {
                if (document.getAttachmentStatus().substring(0,3).equals("Yes")) {
                    uploadIcon = new Button();
                    uploadIcon.setBorders(false);
                    uploadIcon.setIconStyle("attachment");
                    uploadpanel.add(uploadIcon, uploadEastData);
                }
            }
            mainForm.add(uploadpanel, formData);
        }
        uploadpanel.setButtonAlign(HorizontalAlignment.RIGHT);

        uploadButton.addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {

                final Window w = new Window();
                w.setHeading("Upload file");
                w.setModal(true);
                w.setSize(800, 300);
                w.setMaximizable(true);
                w.setToolTip("Upload file");
                w.setUrl(GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + "?module=wicid&action=uploadfile&docname=" + document.getTitle()
                        + "&docid=" + document.getId() + "&topic=" + document.getTopic());
                w.show();
                w.addWindowListener(new WindowListener() {

                    @Override
                    public void windowHide(WindowEvent we) {
                        // check if the attachment exists in the database. if it uploaded
                        // then the file uploaded fine and we can refresh the icon page
                        // otherwise show error message
                        checkAttachment(document.getId());
                    }
                });
            }
        });

        
        mainForm.setButtonAlign(HorizontalAlignment.LEFT);
        editDocumentDialog.setBodyBorder(false);
        if (mode.equals("apo")) {
            editDocumentDialog.setHeading("Edit Course Proposal");
        } else {
            editDocumentDialog.setHeading("Edit document");
        }
        editDocumentDialog.setWidth(500);
        if (mode.equals("apo")) {
            editDocumentDialog.setHeight(420);
        } else {
            editDocumentDialog.setHeight(520);
        }
        editDocumentDialog.setHideOnButtonClick(true);

        editDocumentDialog.setButtons(Dialog.CLOSE);
        editDocumentDialog.getButtonById(Dialog.CLOSE).addSelectionListener(new SelectionListener<ButtonEvent>() {

            @Override
            public void componentSelected(ButtonEvent ce) {
                if (main != null) {
                    main.refreshFileList();
                }
            }
        });
        editDocumentDialog.setButtonAlign(HorizontalAlignment.LEFT);

        editDocumentDialog.add(mainForm);
        }

    public void storeDocumentInfo() {
        String originatingDepartment = deptField.getValue();
        String telNumber = telField.getValue();
        String docTitle = titleField.getValue();
        String group = groupField.getValue().toString();
        String faculty = topicField.getValue();
        String fileUpload = uploadFile.getValue().toString();

        WicidXML wicidxml = new WicidXML("data");
        wicidxml.addElement("originatingdepartment", originatingDepartment);
        wicidxml.addElement("telnumber", telNumber);
        wicidxml.addElement("doctitle", docTitle);
        wicidxml.addElement("group", group);
        wicidxml.addElement("faculty", faculty);
        wicidxml.addElement("fileupload", fileUpload);
        String data = wicidxml.getXml();
    }

    public void show() {
        editDocumentDialog.show();
    }

    public TextField<String> getTitleField() {
        return titleField;
    }

    private void setDepartment() {
        String url = GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + "?module=wicid&action=getdepartment";

        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, url);

        try {
            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        deptField.setValue(response.getText());
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }
    }

    private void updateDocument(String url) {

        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot create new document", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        if (main != null) {
                            main.refreshFileList();
                        }
                        editDocumentDialog.setVisible(false);
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot create document", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot create new document", null);
        }
    }

    public void setSelectedFolder(ModelData selectedFolder) {
        this.selectedFolder = selectedFolder;
        topicField.setValue((String) this.selectedFolder.get("id"));
        topicField.setToolTip((String) this.selectedFolder.get("id"));
    }

    public void checkAttachment(String docid) {
        String url = GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + "?module=wicid&action=checkdocattach&docids=" + docid;
        RequestBuilder builder =
                new RequestBuilder(RequestBuilder.GET, url);

        try {

            Request request = builder.sendRequest(null, new RequestCallback() {

                public void onError(Request request, Throwable exception) {
                    MessageBox.info("Error", "Error, cannot check document attachment", null);
                }

                public void onResponseReceived(Request request, Response response) {
                    if (200 == response.getStatusCode()) {
                        String myResponse = response.getText();
                        if (myResponse.equals("true")) {
                            if (uploadIcon == null) {
                                uploadIcon = new Button();
                                uploadIcon.setIconStyle("attachment");
                                uploadIcon.setBorders(false);
                                uploadpanel.add(uploadIcon, uploadCenterData);

                                //refresh the editing dialog page
                                mainForm.layout();
                                //refresh the main document list panel
                                String params = "?module=wicid&action=getdocuments&mode=" + Constants.main.getMode();
                                Constants.main.getDocumentListPanel().refreshDocumentList(params);
                            }
                        } else {
                            MessageBox.info("Error Uploading", "There was an error uploading the attachment. Please try again!", null);
                        }
                    } else {
                        MessageBox.info("Error", "Error occured on the server. Cannot heck document attachment", null);
                    }
                }
            });
        } catch (RequestException e) {
            MessageBox.info("Fatal Error", "Fatal Error: cannot check document attachment", null);
        }
    }
}

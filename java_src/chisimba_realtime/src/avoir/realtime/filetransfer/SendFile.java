/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package avoir.realtime.filetransfer;

/**
 *
 * @author developer
 */
public class SendFile {

    private String filename;
    private String receipient;
    private boolean accepted;
    private long filesize;
    private String progress;
    private int row;

    public SendFile(String filename, String receipient, boolean accepted, long filesize, String progress, int row) {
        this.filename = filename;
        this.receipient = receipient;
        this.accepted = accepted;
        this.filesize = filesize;
        this.progress = progress;
        this.row = row;
    }

    public int getRow() {
        return row;
    }

    public void setRow(int row) {
        this.row = row;
    }

    public boolean isAccepted() {
        return accepted;
    }

    public void setAccepted(boolean accepted) {
        this.accepted = accepted;
    }

    public String getFilename() {
        return filename;
    }

    public void setFilename(String filename) {
        this.filename = filename;
    }

    public long getFilesize() {
        return filesize;
    }

    public void setFilesize(long filesize) {
        this.filesize = filesize;
    }

    public String getProgress() {
        return progress;
    }

    public void setProgress(String progress) {
        this.progress = progress;
    }

    public String getReceipient() {
        return receipient;
    }

    public void setReceipient(String receipient) {
        this.receipient = receipient;
    }
}

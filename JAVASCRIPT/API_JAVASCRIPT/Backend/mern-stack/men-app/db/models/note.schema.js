import mongoose from "mongoose";
const { Schema, model } = mongoose;

const noteSchema = new Schema({
  title: {
    type: String,
    required: [true, 'El título es obligatorio'],
    validate: {
      validator: function(v) {
        return !/^\d/.test(v);
      },
      message: 'El título no puede empezar por un número'
    }
  },
  text: {
    type: String,
    required: [true, 'El texto es obligatorio']
  },
  status: {
    type: String,
    enum: ["Published", "Draft", "Archived"],
    required: true,
    default: "Draft"
  },
  author: { 
    type: mongoose.Schema.Types.ObjectId, 
    ref: "User", 
    required: true 
  },
}, { 
  timestamps: true 
});

export default model("Note", noteSchema);

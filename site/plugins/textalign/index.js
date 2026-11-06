const alignButton = (align, label) => ({
  label: label,
  icon: "text-" + align,
  click: function () {
    this.command("wrap", "\n::: " + align + "\n", "\n:::\n");
  }
});

panel.plugin("maewest/textalign", {
  textareaButtons: {
    alignleft: alignButton("left", "Align left"),
    aligncenter: alignButton("center", "Align center"),
    alignright: alignButton("right", "Align right")
  }
});
